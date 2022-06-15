<?php
namespace App\DBAL;

class EnumUserRoleType extends EnumType
{
    protected $name = 'enumuserrole';
    protected $values = array('ROLE_CANDIDATE', 'ROLE_RECRUITER', 'ROLE_ADMIN');
}