<?php
namespace App\DBAL;

class EnumUserRoleType extends EnumType
{
    protected $name = 'enumuserrole';
    protected $values = array('candidate', 'recruiter', 'admin');
}