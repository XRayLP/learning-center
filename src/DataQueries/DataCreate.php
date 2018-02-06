<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\DataQueries;


use Contao\Database;

class DataCreate
{
    public static function createDatabaseEntries()
    {
        //tl_page
        $pages = DataSites::getDataSites();
        foreach ($pages as $page) {
            $strKeys = implode(",", array_keys($page));
            $strValues = "'".implode("','", array_values($page))."'";
            Database::getInstance()
                ->query("INSERT INTO tl_page(".$strKeys.")VALUES (".$strValues.");");
        }

        //tl_layout
        $layouts = DataLayouts::getDataLayout();
        foreach ($layouts as $layout) {
            $strKeys = implode(",", array_keys($layout));
            $strValues = "'".implode("','", array_values($layout))."'";
            Database::getInstance()
                ->query("INSERT INTO tl_layout(".$strKeys.")VALUES (".$strValues.");");
        }

        //tl_article
        $articles = DataArticles::getDataArticles();
        foreach ($articles as $article) {
            $strKeys = implode(",", array_keys($article));
            $strValues = "'".implode("','", array_values($article))."'";
            Database::getInstance()
                ->query("INSERT INTO tl_article(".$strKeys.")VALUES (".$strValues.");");
        }

        //tl_content
        $contents = DataContent::getDataContent();
        foreach ($contents as $content) {
            $strKeys = implode(",", array_keys($content));
            $strValues = "'".implode("','", array_values($content))."'";
            Database::getInstance()
                ->query("INSERT INTO tl_content(".$strKeys.")VALUES (".$strValues.");");
        }

        //tl_form
        $forms = DataForms::getDataForms();
        foreach ($forms as $form) {
            $strKeys = implode(",", array_keys($form));
            $strValues = "'".implode("','", array_values($form))."'";
            Database::getInstance()
                ->query("INSERT INTO tl_form(".$strKeys.")VALUES (".$strValues.");");
        }

        //tl_form_field
        $fields = DataFormFields::getDataFormFields();
        foreach ($fields as $field) {
            $strKeys = implode(",", array_keys($field));
            $strValues = "'".implode("','", array_values($field))."'";
            Database::getInstance()
                ->query("INSERT INTO tl_form_field(".$strKeys.")VALUES (".$strValues.");");
        }

        //tl_bs_grid
        $grids = DataGrid::getDataGrid();
        foreach ($grids as $grid) {
            $strKeys = implode(",", array_keys($grid));
            $strValues = "'".implode("','", array_values($grid))."'";
            Database::getInstance()
                ->query("INSERT INTO tl_bs_grid(".$strKeys.")VALUES (".$strValues.");");
        }

        //tl_member_groups
        $groups = DataMemberGroups::getDataMemberGroups();
        foreach ($groups as $group) {
            $strKeys = implode(",", array_keys($group));
            $strValues = "'".implode("','", array_values($group))."'";
            Database::getInstance()
                ->query("INSERT INTO tl_member_group(".$strKeys.")VALUES (".$strValues.");");
        }

        //tl_module
        $modules = DataModules::getDataModules();
        foreach ($modules as $module) {
            $strKeys = implode(",", array_keys($module));
            $strValues = "'".implode("','", array_values($module))."'";
            Database::getInstance()
                ->query("INSERT INTO tl_module(".$strKeys.")VALUES (".$strValues.");");
        }

        //tl_themes
        $themes = DataThemes::getDataThemes();
        foreach ($themes as $theme) {
            $strKeys = implode(",", array_keys($theme));
            $strValues = "'".implode("','", array_values($theme))."'";
            Database::getInstance()
                ->query("INSERT INTO tl_theme(".$strKeys.")VALUES (".$strValues.");");
        }
    }
}