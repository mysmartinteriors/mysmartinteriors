<?php
class Activity extends CI_Model
{
    
	private $table_name='activity_log';
	private static $db;

    function __construct(){
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    static function get_activity_data($module_name,$apidata){
        
        $data=array();

        $module_name=strtolower($module_name);

        switch ($module_name) {

        //customers
        case 'customers_create':
            $data['description']='created a new customer '.$apidata['customer_code'].'('.$apidata['name'].')';
        break;
        
        case 'customers_update':
            $data['description']='updated the customer '.$apidata['customer_code'].'('.$apidata['name'].')';
        break;

        case 'customers_delete':
            $data['description']='deleted the customer '.$apidata['customer_code'].'('.$apidata['name'].')';
        break;

        
        //customer branches
        case 'customer_branches_create':
            $data['description']='created a new branch '.$apidata['branch_code'].'('.$apidata['name'].') for a customer '.$apidata['customers_code'].'('.$apidata['customers_name'].')';
        break;

        case 'customer_branches_update':
            $data['description']='updated the branch '.$apidata['branch_code'].' ('.$apidata['name'].') of a customer '.$apidata['customers_code'].' ('.$apidata['customers_name'].')';
        break;

        case 'customer_branches_delete':
            $data['description']='deleted the branch '.$apidata['branch_code'].' ('.$apidata['name'].') of a customer '.$apidata['customers_code'].' ('.$apidata['customers_name'].')';
        break;


        //customer branches persons
        case 'customer_branches_persons_create':
            $data['description']='added a new contact person '.$apidata['name'].', '.$apidata['phone'].' for the branch '.$apidata['customer_branches_branch_code'].'('.$apidata['customer_branches_name'].') of a customer '.$apidata['customers_code'].'('.$apidata['customers_name'].')';
        break;

        case 'customer_branches_persons_update':
             $data['description']='updated the contact person '.$apidata['name'].', '.$apidata['phone'].' belongs to the branch '.$apidata['customer_branches_branch_code'].'('.$apidata['customer_branches_name'].') of a customer '.$apidata['customers_code'].'('.$apidata['customers_name'].')';
        break;

        case 'customer_branches_persons_delete':
             $data['description']='deleted a contact person '.$apidata['name'].', '.$apidata['phone'].' belongs to the branch '.$apidata['customer_branches_branch_code'].'('.$apidata['customer_branches_name'].') of a customer '.$apidata['customers_code'].'('.$apidata['customers_name'].')';
        break;


        //organization        
        case 'organization_update':
            $data['description']='updated the details of an organization - '.$apidata['name'];
        break;

        
        //organization branches
        case 'organization_branches_create':
            $data['description']='created a new branch '.$apidata['name'].' of an organization '.$apidata['organization_name'];
        break;
        
        case 'organization_branches_update':
            $data['description']='updated the branch '.$apidata['name'].' of an organization '.$apidata['organization_name'];
        break;

        case 'organization_branches_delete':
            $data['description']='deleted the branch '.$apidata['name'].' of an organization '.$apidata['organization_name'];
        break;


        //department
        case 'department_create':
            $data['description']='created a new department '.$apidata['name'].' ';
            if($apidata['parent_name']){
                $data['description'].='under '.$apidata['parent_name'].' ';
            }
            $data['description'].='for the organization branch '.$apidata['organization_branches_name'];
        break;
        
        case 'department_update':
            $data['description']='updated the department '.$apidata['name'].' ';
            if($apidata['parent_name']){
                $data['description'].='which is under '.$apidata['parent_name'].' ';
            }
            $data['description'].='of an organization branch '.$apidata['organization_branches_name'];
        break;

        case 'department_delete':
            $data['description']='deleted the department '.$apidata['name'].' ';
            if($apidata['parent_name']){
                $data['description'].='which is under '.$apidata['parent_name'].' ';
            }
            $data['description'].='of an organization branch '.$apidata['organization_branches_name'];
        break;


        //users
        case 'users_create':
            $data['description']='created a new user '.$apidata['login_id'].' ('.$apidata['first_name'].' '.$apidata['last_name'].') ';
            if($apidata['roles_name']){
                $data['description'].='with a role '.$apidata['roles_name'].' ';
            }
            if($apidata['departments_name']){
                $data['description'].='and allocated to '.$apidata['departments_name'].' department ';
            }
            if($apidata['organization_branches_name']){
                $data['description'].='under '.$apidata['organization_branches_name'].' branch';
            }
        break;

        case 'users_update':
            $data['description']='updated the user '.$apidata['login_id'].' ('.$apidata['first_name'].' '.$apidata['last_name'].') ';
            if($apidata['roles_name']){
                $data['description'].='with '.$apidata['roles_name'].' role ';
            }
            if($apidata['departments_name']){
                $data['description'].='and allocated to '.$apidata['departments_name'].' department ';
            }
            if($apidata['organization_branches_name']){
                $data['description'].='under '.$apidata['organization_branches_name'].' branch';
            }
        break;

        case 'users_delete':
            $data['description']='deleted the user '.$apidata['login_id'].' ('.$apidata['first_name'].' '.$apidata['last_name'].') ';
            if($apidata['roles_name']){
                $data['description'].='with '.$apidata['roles_name'].' role ';
            }
            if($apidata['departments_name']){
                $data['description'].='and allocated to '.$apidata['departments_name'].' department ';
            }
            if($apidata['organization_branches_name']){
                $data['description'].='under '.$apidata['organization_branches_name'].' branch';
            }
        break;

        //roles
        case 'roles_create':
            $data['description']='created a new role '.$apidata['name'];
            if($apidata['departments_name']){
                $data['description'].=' for '.$apidata['departments_name'].' department ';
            }
            if($apidata['organization_branches_name']){
                $data['description'].='under '.$apidata['organization_branches_name'].' branch';
            }
        break;

        case 'roles_update':
            $data['description']='updated the role '.$apidata['name'];
            if($apidata['departments_name']){
                $data['description'].=' of '.$apidata['departments_name'].' department ';
            }
            if($apidata['organization_branches_name']){
                $data['description'].='under '.$apidata['organization_branches_name'].' branch';
            }
        break;  

        case 'roles_delete':
            $data['description']='deleted the role '.$apidata['name'];
            if($apidata['departments_name']){
                $data['description'].=' of '.$apidata['departments_name'].' department ';
            }
            if($apidata['organization_branches_name']){
                $data['description'].='under '.$apidata['organization_branches_name'].' branch';
            }
        break;

        case 'roles_permission_update':
            $data['description']='updated the permissions of a role '.$apidata['name'];
            if($apidata['departments_name']){
                $data['description'].=' of '.$apidata['departments_name'].' department ';
            }
            if($apidata['organization_branches_name']){
                $data['description'].='under '.$apidata['organization_branches_name'].' branch';
            }
        break;                
        
        
        //modules
        case 'modules_create':
            $data['description']='created a new module '.$apidata['name'];
        break;
        
        case 'modules_update':
            $data['description']='updated the module '.$apidata['name'];
        break;

        case 'modules_delete':
            $data['description']='deleted the module '.$apidata['name'];
        break;  

        
        //module permissions
        case 'module_permissions_create':
            $data['description']='created a new permission '.$apidata['name'].' for '.$apidata['modules_name'].' module';
        break;
        
        case 'module_permissions_update':
            $data['description']='updated the permission '.$apidata['name'].' belongs to '.$apidata['modules_name'].' module';
        break;

        case 'module_permissions_delete':
            $data['description']='deleted the permission '.$apidata['name'].' belongs to '.$apidata['modules_name'].' module';
        break;                

        
        //service categories
        case 'categories_create':
            $data['description']='created a new category '.$apidata['text'];
        break;
        
        case 'categories_update':
            $data['description']='updated the category '.$apidata['text'];
        break;

        case 'categories_delete':
            $data['description']='deleted the category '.$apidata['text'];
        break;  

        
        //services
        case 'services_create':
            $data['description']='created a new service '.$apidata['service_code'].', '.$apidata['name'];
        break;
        
        case 'services_update':
            $data['description']='updated the service '.$apidata['service_code'].', '.$apidata['name'];
        break;

        case 'services_delete':
            $data['description']='deleted the service '.$apidata['service_code'].', '.$apidata['name'];
        break;      


        //plans
        case 'plans_create':
            $data['description']='created a new plan '.$apidata['plan_code'].' for the '.rtrim($apidata['reference_type'],"s").' - '.$apidata['reference_code'].'('.$apidata['reference_name'].')';
        break;
        
        case 'plans_update':
            $data['description']='updated the plan '.$apidata['plan_code'].' of a '.rtrim($apidata['reference_type'],"s").' - '.$apidata['reference_code'].'('.$apidata['reference_name'].')';
        break;

        case 'plans_delete':
            $data['description']='deleted the plan '.$apidata['plan_code'].' of a '.rtrim($apidata['reference_type'],"s").' - '.$apidata['reference_code'].'('.$apidata['reference_name'].')';
        break;
        

        //vendors
        case 'vendors_create':
            $data['description']='created a new vendor '.$apidata['login_id'].', '.$apidata['name'];
        break;
        
        case 'vendors_update':
            $data['description']='updated the vendor '.$apidata['login_id'].', '.$apidata['name'];
        break;

        case 'vendors_delete':
            $data['description']='deleted the vendor '.$apidata['login_id'].', '.$apidata['name'];
        break;


        //service_templates
        case 'service_templates_create':
            $data['description']='created a new service template '.$apidata['form_code'].', '.$apidata['name'].' for the customer '.$apidata['customers_code'].'-'.$apidata['customers_name'];
        break;
        
        case 'service_templates_update':
            $data['description']='updated the service template '.$apidata['form_code'].', '.$apidata['name'].' of a customer '.$apidata['customers_code'].'-'.$apidata['customers_name'];
        break;

        case 'service_templates_delete':
            $data['description']='deleted the service template '.$apidata['form_code'].', '.$apidata['name'].' of a customer '.$apidata['customers_code'].'-'.$apidata['customers_name'];
        break;


        //checklists
        case 'checklists_create':
            $data['description']='created a new checklist '.$apidata['name'].' for '.$apidata['check_type_name'];
        break;
        
        case 'checklists_update':
            $data['description']='updated the checklist '.$apidata['name'].' of '.$apidata['check_type_name'];
        break;

        case 'checklists_delete':
            $data['description']='deleted the checklist '.$apidata['name'].' of '.$apidata['check_type_name'];
        break;


        //workflow
        case 'workflow_create':
            $data['description']='created a new workflow state '.$apidata['name'];
        break;
        
        case 'workflow_update':
            $data['description']='updated the workflow '.$apidata['name'];
        break;

        case 'workflow_delete':
            $data['description']='deleted the workflow '.$apidata['name'];
        break;


        //workorders
        case 'workorders_create':
            $data['description']='created a new workorder '.$apidata['code'].' for the customer '.$apidata['customers_name'].', '.$apidata['customer_branches_name'].', '.$apidata['customer_branch_person_name'];
        break;
        
        case 'workorders_update':
            $data['description']='updated the workorder '.$apidata['code'].' of the customer '.$apidata['customers_name'].', '.$apidata['customer_branches_name'].', '.$apidata['customer_branch_person_name'];
        break;

        case 'workorders_delete':
            $data['description']='deleted the workorder '.$apidata['code'].' of the customer '.$apidata['customers_name'].', '.$apidata['customer_branches_name'].', '.$apidata['customer_branch_person_name'];
        break;

        case 'workorder_reports_zip_download':
            $data['description']='downloaded the zip file of all the report of an workorder '.$apidata['code'].' of the customer '.$apidata['customers_name'].', '.$apidata['customer_branches_name'].', '.$apidata['customer_branch_person_name'];
        break;


        //workorder profiles
        case 'workorder_profiles_create':
            $data['description']='added a profile '.$apidata['name'];
            if(!empty($apidata['ref_id'])){
                $data['description'].=' ('.$apidata['ref_id'].')';
            }
            $data['description'].=' for the workorder '.$apidata['workorder_code'];
        break;
        
        case 'workorder_profiles_update':
            $data['description']='updated the profile '.$apidata['name'];
            if(!empty($apidata['ref_id'])){
                $data['description'].=' ('.$apidata['ref_id'].')';
            }
            $data['description'].=' of an workorder '.$apidata['workorder_code'];
        break;

        case 'workorder_profiles_delete':
            $data['description']='deleted the profile '.$apidata['name'];
            if(!empty($apidata['ref_id'])){
                $data['description'].=' ('.$apidata['ref_id'].')';
            }
            $data['description'].=' of an workorder '.$apidata['workorder_code'];
        break;

        case 'workorder_profiles_reports':
            $data['description']='downloaded the report of a profile '.$apidata['name'];
            if(!empty($apidata['ref_id'])){
                $data['description'].=' ('.$apidata['ref_id'].')';
            }
            $data['description'].=' of an workorder '.$apidata['workorder_code'];
        break;

        case 'profile_reports_zip_download':
            $data['description']='downloaded the zip file of all the report of a profile '.$apidata['name'];
            if(!empty($apidata['ref_id'])){
                $data['description'].=' ('.$apidata['ref_id'].')';
            }
            $data['description'].=' of an workorder '.$apidata['workorder_code'];
        break;


         //workorder profile checks

        case 'workorder_profiles_checks_create':
            $data['description']='added a check '.$apidata['services_name'].' to the profile '.$apidata['workorders_profiles_name'].' of an workorder '.$apidata['workorder_code'];
        break;

        case 'workorder_profiles_checks_update':
            $data['description']='updated the check '.$apidata['services_name'].' of the profile '.$apidata['workorders_profiles_name'].' of an workorder '.$apidata['workorder_code'];
        break;

        case 'workorder_profiles_checks_delete':
            $data['description']='deleted the check '.$apidata['services_name'].' from the profile '.$apidata['workorders_profiles_name'].' of an workorder '.$apidata['workorder_code'];
        break;
        
        case 'workorder_profiles_checks_status_update':
            $data['description']='updated the status of a check '.$apidata['services_name'].' belongs to the profile '.$apidata['workorders_profiles_name'].' of an workorder '.$apidata['workorder_code'].' to '.$apidata['status_name'];
        break;

        case 'workorder_profiles_checks_reports':
            $data['description']='downloaded the report of a check '.$apidata['services_name'].' from the profile '.$apidata['workorders_profiles_name'].' of an workorder '.$apidata['workorder_code'];
        break;

        case 'workorder_profiles_checks_bulk_status_update':
            $data['description']='updated the status in bulk using grid editor of a workorder '.$apidata['code'];
        break;

        case 'workorder_profiles_checks_bulk_edit_update':
            $data['description']='updated the data in bulk using grid editor of a workorder '.$apidata['code'];
        break;

        default:
            $data=array(
                'action'=>'undefined',
                'module'=>'undefined',
                'data_id'=>'undefined',
                'description'=>'undefined'
            );
        break;
        }

        if(!empty($apidata)){
            $data['data_id']=$apidata['id'];
            preg_match_all('/(.*)_(.*)/s', $module_name, $matches);
            $count= count($matches);
            if($count>0){
                $data['action']=$matches[$count-1][0];
                $data['module']=$matches[$count-2][0];
            }
        }
        return $data;
    }

    static function module_log($module,$usertype,$apidata)
    {
        $usertype=ucfirst(strtolower($usertype));
        $data=Activity::get_activity_data($module,$apidata);
        $data['ip_address']=get_ipaddress();        
        $data['reference_id']=$usertype::get_userId();
        $data['reference_name']=$usertype::get_userName();
        $data['reference_type']=$usertype::get_ReferType();
        $ci =& get_instance();
        $apidata=$ci->curl->execute('activity_log',"POST",$data);
    }    
	
	
    
}
