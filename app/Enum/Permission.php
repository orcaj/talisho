<?php


namespace App\Enum;


class Permission extends Enum
{
    const VIEW_EMPLOYEES = 'view employees';
    const CREATE_EMPLOYEES = 'create users';
    const DELETE_EMPLOYEES = 'delete users';

    const EDIT_PROJECTS = 'edit projects';
    const CREATE_PROJECTS = 'create projects';
    const VIEW_PROJECT_DISCIPLINE_COMMENTS = 'view project discipline comments';
    const VIEW_ALL_PROJECT_DISCIPLINES = 'view all project disciplines';

    const EDIT_ORGANIZATIONS = 'edit organizations';

    const VIEW_ALL_DOCUMENTATION = 'view all documentation';
    const VIEW_ALL_COMMUNICATION = 'view all communication';
    const VIEW_ALL_LIABILITY = 'view all liability';

    const VIEW_ALL_PROJECT_FILES = 'view all project files';

    const CREATE_DESIGN_DOCUMENTS = 'upload design documents';

    const AUTO_APPROVE_DOCUMENTS = 'auto approve documents';
}
