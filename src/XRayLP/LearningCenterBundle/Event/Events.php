<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Event;


class Events
{
    //Project Events
    const PROJECT_PRE_LOAD = 'project.pre.load';
    const PROJECT_CREATE_SUCCESS_EVENT = 'project.create.success';
    const PROJECT_CREATE_FAILURE_EVENT = 'project.create.failure';
    const PROJECT_UPDATE_SUCCESS_EVENT = 'project.update.success';
    const PROJECT_UPDATE_FAILURE_EVENT = 'project.update.failure';
    const PROJECT_PRE_DELETE_EVENT = 'project.delete.pre';
    const PROJECT_POST_DELETE_SUCCESS_EVENT = 'project.delete.post.success';
    const PROJECT_MEMBERS_ADD_SUCCESS_EVENT = 'project.members.add.success';
    const PROJECT_MEMBERS_ADD_FAILURE_EVENT = 'project.members.add.failure';
    const PROJECT_MEMBERS_PROMOTE_LEADER_SUCCESS_EVENT = 'project.members.promote.leader.success';
    const PROJECT_MEMBERS_PROMOTE_LEADER_FAILURE_EVENT = 'project.members.promote.leader.failure';
    const PROJECT_MEMBERS_PROMOTE_ADMIN_SUCCESS_EVENT = 'project.members.promote.admin.success';
    const PROJECT_MEMBERS_PROMOTE_ADMIN_FAILURE_EVENT = 'project.members.promote.admin.failure';
    const PROJECT_MEMBERS_DEGRADE_SUCCESS_EVENT = 'project.members.degrade.success';
    const PROJECT_MEMBERS_DEGRADE_FAILURE_EVENT = 'project.members.degrade.failure';
    const PROJECT_MEMBERS_REMOVE_SUCCESS_EVENT = 'project.members.remove.success';
    const PROJECT_MEMBERS_REMOVE_FAILURE_EVENT = 'project.members.remove.failure';
    const PROJECT_EVENTS_ADD_SUCCESS_EVENT = 'project.events.add.success';
    const PROJECT_EVENTS_ADD_FAILURE_EVENT = 'project.events.add.failure';
    const PROJECT_EVENTS_REMOVE_SUCCESS_EVENT = 'project.events.remove.success';
    const PROJECT_EVENTS_REMOVE_FAILURE_EVENT = 'project.events.remove.failure';

    //Group Events
    const GROUP_CREATE_SUCCESS_EVENT = 'project.create.success';

    //Filemanager Events
    const FILE_UPLOAD = 'file.upload';


}