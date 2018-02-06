<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Classes;

/**
 * Helper class for user management.
 *
 * @author Niklas Loos <https://github.com/XRayLP>
 */
class UserHelper
{
    /**
     * Check whether a user is a teacher or not.
     *
     * @param object $user
     * @return bool
     */
    public static function isTeacher($user){
        $teacherGroupID = 4;
        $groups = deserialize($user->groups);

        //check whether the student is part of the teacher group
        foreach ($groups as &$group) {
            if ($group == $teacherGroupID) {
                return true;
            }
        }
        return false;
        
    }

    /**
     * Check whether a user is a student or not.
     *
     * @param object $user
     * @return bool
     */
    public static function isStudent($user){
        $teacherGroupID = 1;
        $groups = deserialize($user->groups);

        //check whether the student is part of the student group
        foreach ($groups as &$group) {
            if ($group == $teacherGroupID) {
                return true;
            }
        }
        return false;
        
    }

    /**
     * Get the class group of an user.
     *
     * @param object $user
     * @return object
     */
    public static function getClass($user){
        $classKeyWord = "Klasse";
        $groups = deserialize($user->groups);

        //Find the group which has the Keyword 'Klasse'
        foreach ($groups as &$group) {
            $group = \MemberGroupModel::findByPk($group);
            if (strpos($group->name, $classKeyWord) !== false ) {
                return $group;
            }
        }
    }

    /**
     * Get all course groups of an user.
     *
     * @param object $user
     * @return array
     */
    public static function getCourses($user){
        $classKeyWord = "Kurs";
        $courses = array();
        $groups = deserialize($user->groups);
        
        //Find the group which has the Keyword 'Kurs'
        foreach ($groups as &$group) {
            $group = \MemberGroupModel::findByPk($group);
            if (strpos($group->name, $classKeyWord) !== false ) {
                array_push($courses, $group);
            }
        }
        return $courses;
    }
}

