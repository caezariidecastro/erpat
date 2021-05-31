<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Address extends MY_Controller{

    public function __construct(){
		parent ::__construct();
        $this->load->library('Uuid');
        $this->load->model('Address_model');
    }
    
    function index()
	{
        redirect('forbidden');
    }

    function get() {
        $this->header_application_json();

        validate_submitted_data(array(
            "uuid" => "required",
        ));

        $uuid = $this->input->post('uuid');

        echo json_encode(
            array(
                "data" => $this->Address_model->get_details( array("uuid" => $uuid) )->row()
            )
        );
    }

    function list() {
        $this->header_application_json();
        echo json_encode(
            array(
                "data" => $this->Address_model->get_all()->result()
            )
        );
    }

    function save() {
        $this->header_application_json();

        validate_submitted_data(array(
            "title" => "required",
            "house" => "required",
            "address" => "required",
            "latitude" => "required",
            "longitude" => "required",
        ));

        $data = $this->validate_post_data([
            "title", "house", "address", "landmark", "zipcode", "latitude", "longitude"
        ]);

        $id = $this->input->post('id');
        if(!$id) {
            $data['uuid'] = $this->uuid->v4();
            $data['created_by'] = $this->login_user->uuid;
        }
        
        $id = $this->Address_model->save($data);

        if($id) {
            echo json_encode(
                array(
                    "success" => true,
                    "data" => $this->Address_model->get_details(
                        array("id"=>$id)
                    )->row(),
                )
            );
        } else {
            echo json_encode(
                array(
                    "success" => false,
                    "message" => "Something went wrong.",
                )
            );
        }
        
    }

    function edit() {
        $this->header_application_json();

        validate_submitted_data(array(
            "uuid" => "required"
        ));

        $data = $this->validate_post_data([
            "title", "house", "address", "landmark", "zipcode", "latitude", "longitude"
        ]);

        $uuid = $this->input->post('uuid');
        if($uuid) {
            $id = $this->Address_model->update_where($data, array("uuid"=>$uuid));
            echo json_encode(
                array(
                    "success" => true,
                    "message" => $this->Address_model->get_details(
                        array("id"=>$id)
                    )->row(),
                )
            );
            exit;
        }

        echo json_encode(
            array(
                "success" => false,
                "message" => "Row does not exist."
            )
        );
    }

    function delete() {
        $this->header_application_json();

        validate_submitted_data(array(
            "uuid" => "required",
        ));

        $uuid = $this->input->post('uuid');
        $address = $this->Address_model->get_details( array("uuid" => $uuid) )->row();

        if(!$address) {
            echo json_encode(
                array(
                    "success" => false,
                    "message" => "Data row not found."
                )
            );
            exit;
        }

        $success = $this->Address_model->delete($address->id);
        if(!$success) {
            echo json_encode(
                array(
                    "success" => false,
                    "message" => "Data row not deleted."
                )
            );
            exit;
        }

        echo json_encode(
            array(
                "success" => true,
                "data" => $address
            )
        );
        
    }
    
}
