<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

$GLOBALS['TL_DCA']['tl_timetable'] = array(

    //Config
    'config' => array(
        'dataContainer'     => 'Table',
        'switchToEdit'      => true,
        'enableVersioning'  => true,
        'sql' => array(
            'keys' => array(
                'id' => 'primary'
            )
        )
    ),

    //List
    'list' => array(

        'sorting' => array(
            'mode'          => 1,
            'fields'        => array('name'),
            'flag'          => 1,
            'panelLayout'   => 'filter;search,limit'
        ),
        'label' => array(
            'fields' => array('name'),
            'format' => '%s'
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_timetable']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.svg'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_timetable']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.svg',
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_timetable']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_timetable']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )

    ),

    // Palettes
    'palettes' => array(
        'default' => '{main_legend},day,lesson,course'
    ),

    //Fields
    'fields' => array(
        'id' => array(
            'sql'   => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp'    => array(
            'sql'   => "int(10) unsigned NOT NULL default '0'"
        ),
        'day'   => array(
            'label' => &$GLOBALS['TL_LANG']['tl_timetable']['day'],
            'search'    => true,
            'inputType' => 'select',
            'options'    => array(
                '1' => &$GLOBALS['TL_LANG']['tl_timetable']['day']['options']['monday'],
                '2' => &$GLOBALS['TL_LANG']['tl_timetable']['day']['options']['tuesday'],
                '3' => &$GLOBALS['TL_LANG']['tl_timetable']['day']['options']['wednesday'],
                '4' => &$GLOBALS['TL_LANG']['tl_timetable']['day']['options']['thursday'],
                '5' => &$GLOBALS['TL_LANG']['tl_timetable']['day']['options']['friday']
            ),
            'eval'      => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'       => "int(10) Not NULL default '0'"

        ),
        'lesson'   => array(
            'label' => &$GLOBALS['TL_LANG']['tl_timetable']['lesson'],
            'search'    => true,
            'inputType' => 'select',
            'options'    => array(
                '1' => &$GLOBALS['TL_LANG']['tl_timetable']['lesson']['options']['1'],
                '2' => &$GLOBALS['TL_LANG']['tl_timetable']['lesson']['options']['2'],
                '3' => &$GLOBALS['TL_LANG']['tl_timetable']['lesson']['options']['3'],
                '4' => &$GLOBALS['TL_LANG']['tl_timetable']['lesson']['options']['4'],
                '5' => &$GLOBALS['TL_LANG']['tl_timetable']['lesson']['options']['5'],
                '6' => &$GLOBALS['TL_LANG']['tl_timetable']['lesson']['options']['6'],
                '7' => &$GLOBALS['TL_LANG']['tl_timetable']['lesson']['options']['7'],
                '8' => &$GLOBALS['TL_LANG']['tl_timetable']['lesson']['options']['8'],
                '9' => &$GLOBALS['TL_LANG']['tl_timetable']['lesson']['options']['9']
            ),
            'eval'      => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'       => "int(10) Not NULL default '0'"

        ),
        'course' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_timetable']['course'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'options_callback'        => array('tl_timetable', 'getAllCourses'),
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
            'sql'                     => "blob NULL"
        )
    )
);

class tl_timetable extends Backend
{
    /**
     * Get all course groups.
     *
     * @return array
     */
    public function getAllCourses()
    {
        $classKeyWord = "Kurs";
        $courses = array();
        $groups = \Contao\MemberGroupModel::findAll();

        //Find the group which has the Keyword 'Klasse'
        foreach ($groups as &$group) {
            if (strpos($group->name, $classKeyWord) !== false ) {
                $courses[$group->id] = $group->name;
            }
        }
        return $courses;
    }
}
