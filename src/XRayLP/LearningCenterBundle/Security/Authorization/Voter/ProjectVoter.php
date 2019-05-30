<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Security\Authorization\Voter;


use Contao\FrontendUser;
use Contao\StringUtil;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\Project;

// Klasse erbt aus der Voter Klasse
class ProjectVoter extends Voter
{
    // Variable für das MemberRepository Objekt
    private $memberRepository;

    // Doctrine Service wird in die Klasse implementiert
    public function __construct(RegistryInterface $doctrine)
    {
        // Variable wird ein Objekt zugeordnet
        $this->memberRepository = $doctrine->getRepository(Member::class);
    }

    // Konstanten für die einzelnen Berechtigungen
    const USE = 'project'; //können die Projektfunktion im wesentlichen Nutzen
    const CREATE = 'project.create'; // können Projekte erstellen
    const LEAD = 'project.lead'; // können Projektleiter werden
    const LIST_ALL = 'project.list.all'; //können alle Projekte einsehen

    //benötigen Projekt Objekt
    const VIEW = 'project.view'; // können ein bestimmtes Projekt einsehen
    const EDIT = 'project.edit'; // können die Daten eines bestimmten Objekts verändern
    const REMOVE = 'project.remove'; // können ein Projekt löschen
    const CONFIRM = 'project.confirm'; // können ein Projekt bestätigen

    //benötigen Projekt und Event Objekt
    const EVENT_ADD = 'project.event.add'; // können einem Projekt ein Termin hinzufügen
    const EVENT_REMOVE = 'project.event.remove'; //können einem Projekt ein Termin entfernen

    /**
     * Entscheidet ob eine zu überprüfende Berechtigung von diesem Voter unterstütz wird.
     *
     * @param string $attribute Attribut
     * @param mixed $subject Objekt
     *
     * @return bool True wird es unterstützt?
     */
    protected function supports($attribute, $subject)
    {
        // Überprüfung, ob das zu überpüfende Attribut in dem Array aus den oben angelgeten Konstanten enthalten ist
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::REMOVE, self::CREATE, self::LEAD, self::CONFIRM, self::EVENT_ADD, self::EVENT_REMOVE))){
            return false;
        }

        // Überprüfung, ob für die Berechtigungen, die eine Projekt Objekt benötigen, auch ein Projekt Objekt übergeben wurde
        if (!$subject instanceof Project && !in_array($attribute, [self::USE, self::CREATE, self::LEAD, self::LIST_ALL])){
            return false;
        }

        // Ansonsten wird einfach true übergeben und die Berechtigung wird von diesem Voter unterstützt
        return true;
    }

    /**
     * Anhand des Attributes wird ermittelt, welche Funktion zur Überprüfung der Berechtigung genutzt werden soll.
     * $attribute und $subject haben schon die support() Abfrage bestanden
     *
     * @param string $attribute Berechtigung
     * @param mixed $subject Objekt
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // Nutzer Objekt wird aus dem Token geladen
        $user = $token->getUser();

        // Überprüfung, ob nutzer als FrontendUser angemeldet ist
        if (!$user instanceof FrontendUser) {
            return false;
        }

        // Umwandlung des FrontendUser Objekts zu einem Member Entity
        $member = $this->memberRepository->findOneById($user->id);

        // $subjekt Objekt wird als Project Entity angenommen
        $project = $subject;

        // Zuordnung der Attribute zu den einzelnen Überprüfungsfunktionen
        switch ($attribute){
            case self::USE:
                return $this->canUse($member);
            case self::CREATE:
                return $this->canCreate($member);
            case self::LEAD:
                return $this->canLead($member);
            case self::LIST_ALL:
                return $this->canListAll($member);
            case self::VIEW:
                return $this->canView($project, $member);
            case self::EDIT:
                return $this->canEdit($project, $member);
            case self::REMOVE:
                return$this->canRemove($project, $member);
            case self::CONFIRM:
                return $this->canConfirm($project, $member);
            case self::EVENT_ADD:
                return $this->canAddEvent($project, $member);
            case self::EVENT_REMOVE:
                return $this->canRemoveEvent($project, $member);
        }

        // !dieser Code sollte niemals erreicht werden!
        throw new \LogicException('This code should not be reached!');
    }

    private function canUse(Member $member)
    {
        return $member instanceof Member;
    }

    private function canCreate(Member $member)
    {
        return true;
    }

    /*
     * Ist das Mitglied berechtigt ein Projekt zu leiten?
     */
    private function canLead(Member $member)
    {
        // Mitglied = Lehrer, Planer oder Admin?
        return $member->isTeacher() || $member->isPlanner() || $member->isAdmin();
    }

    /*
     * Ist das Mitglied berechtigt alle Projekte einzusehen?
     */
    private function canListAll(Member $member)
    {
        // Mitglied = Admin?
        return $member->isAdmin();
    }

    /*
     * Ist das Mitglied berechtigt ein best. Projekt einzusehen?
     */
    private function canView(Project $project, Member $member)
    {
        // Mitglied = Mitglied des Projektes?
        return $this->isMember($project, $member);
    }

    /*
     * Ist das Mitglied berechtigt ein Projekt zu verändern?
     */
    private function canEdit(Project $project, Member $member)
    {
        // Mitglied = Admin oder Leiter des Projektes?
        return $this->isAdmin($project, $member) || $this->isLeader($project, $member);
    }

    private function canRemove(Project $project, Member $member)
    {
        return $this->isLeader($project, $member);
    }

    private function canConfirm(Project $project, Member $member)
    {
        return $this->isLeader($project, $member);
    }

    private function canAddEvent(Project $project, Member $member)
    {
        return $this->isAdmin($project, $member) || $this->isLeader($project, $member);
    }

    private function canRemoveEvent(Project $project, Member $member)
    {
        return $this->isAdmin($project, $member) || $this->isLeader($project, $member);
    }


    //Helper Functions

    private function isLeader(Project $project, Member $member)
    {
        return ($this->isMember($project, $member)) && ($project->getLeader() == $member);
    }

    private function isAdmin(Project $project, Member $member)
    {
        return ($this->isMember($project, $member)) && ($project->getAdmins()->contains($member));
    }

    private function isMember(Project $project, Member $member)
    {
        //check whether user is already part of this project
        return $member->getGroups()->contains($project->getGroupId());
    }


}