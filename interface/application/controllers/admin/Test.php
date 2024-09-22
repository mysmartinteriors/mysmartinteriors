<?php
function export_get()
{
    $param_default = array('search', 'sortby', 'orderby');
    $parameters = $this->input->get();
    $diff = array();
    $data = array();
    $data['data_list'] = array();
    $search = "";
    $perpage = 10;
    $page = 1;
    $sortby = $this->table . ".id";
    $orderby = "DESC";
    $all = true;

    if (!empty($parameters)) {
        $parem_key = array_keys($parameters);
        $diff = array_diff($parem_key, $param_default);
        $intersect = array_intersect($parem_key, $param_default);
    }

    if (!empty($intersect)) {
        foreach ($intersect as $inst) {
            $rml = str_replace('-', '.', $parameters[$inst]);
            $$inst = $rml;
        }
    }
    $filter_data[0]['type'] = 'search';
    $filter_data[0]['value'] = $search;

    if (!empty($diff)) {
        $i = count($filter_data);
        foreach ($diff as $p) {
            if ($p != 'date_type' && $p != 'date_range') {
                if (!empty($this->input->get($p))) {
                    $pa = str_replace('-', '.', $p);
                    $filter_data[$i]['type'] = $pa;
                    $filter_data[$i]['value'] = $this->input->get($p);
                }
                $i++;
            }
        }
    }
    //print_r($filter_data);

    $date_type = $this->input->get('date_type');
    $date_range = $this->input->get('date_range');
    $from_date = '';
    $to_date = '';
    if (!empty($date_type) && !empty($date_range)) {
        $fromTime = substr($date_range, 0, 10);
        $toTime = substr($date_range, 13, 10);
        $fromTime = $fromTime != "" ? custom_date("Y-m-d", $fromTime) : "";
        $toTime = $toTime != "" ? custom_date("Y-m-d", $toTime) : "";
        $from_date = $fromTime . ' 00:00:00';
        $to_date = $toTime . ' 23:59:59';
    }

    $getData = $this->customer_workorder_checks_model->get_export($filter_data, $sortby, $orderby, $date_type, $from_date, $to_date);

    if ($getData->num_rows() > 0) {
        $rows = $getData->result_array();

        $excel_labels = array('Profile ID', 'Allocated Date', 'Name');
        $excel_fields = array('workorders_profiles_code', 'workorders_profiles_created_at', 'workorders_profiles_name');

        $counts = array();
        $service_name = array();
        $checks_data = array();

        $grouped_data = array();
        foreach ($rows as $data) {
            $wp_code = $data['workorders_profiles_code'];
            if (!isset($grouped_data[$wp_code])) {
                $grouped_data[$wp_code] = array(
                    'workorder_code' => $data['workorder_code'],
                    'workorders_profiles_code' => $data['workorders_profiles_code'],
                    'workorders_profiles_ref_id' => $data['workorders_profiles_ref_id'],
                    'workorders_profiles_name' => $data['workorders_profiles_name'],
                    'workorders_profiles_phone' => $data['workorders_profiles_phone'],
                    'workorders_profiles_email' => $data['workorders_profiles_email'],
                    'workorders_profiles_status' => $data['workorders_profiles_status'],
                    'workorders_profiles_created_at' => $data['created_at'],
                    'workorders_profiles_updated_date' => $data['workorders_profiles_updated_date'],
                    'checks_data' => array()
                );
            }
            if (isset($grouped_data[$wp_code])) {
                // unset($data[''])
                unset($data['workorder_code']);
                unset($data['workorders_profiles_code']);
                unset($data['workorders_profiles_ref_id']);
                unset($data['workorders_profiles_name']);
                unset($data['workorders_profiles_phone']);
                unset($data['workorders_profiles_email']);
                unset($data['workorders_profiles_status']);
                unset($data['workorders_profiles_created_at']);
                unset($data['workorders_profiles_updated_date']);
                $grouped_data[$wp_code]['checks_data'][] = $data;
            }
        }





        $count = array();
        foreach ($grouped_data as $data) {

            $count[] = $this->check_count($data['checks_data']);
        }

        $counts = array();
        for ($i = 0; $i < count($count); $i++) {
            $counts[] = array_count_values($count[$i]);
        }


        $excel_component = array();
        for ($k = 0; $k < count($grouped_data); $k++) {
            foreach ($counts[$k] as $value => $count) {
                if ($count > 1) {
                    for ($j = 1; $j <= $count; $j++) {
                        if (!in_array($value . '-' . $j, $excel_component)) {
                            array_push($excel_component, $value . '-' . $j);
                        }
                    }
                } else {
                    if (!in_array($value . '-1', $excel_component)) {
                        array_push($excel_component, $value . '-1');
                    }
                }

            }
        }
        sort($excel_component);

        array_push($excel_component, 'Final Status');
        array_push($excel_component, 'Completed Date');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        for ($i = 0; $i < count($excel_labels); $i++) {
            $col_name = Coordinate::stringFromColumnIndex($i + 1);
            $sheet->setCellValue($col_name . '1', $excel_labels[$i]);
        }

        for ($i = 3; $i < count($excel_component) + 3; $i++) {
            $col_name = Coordinate::stringFromColumnIndex($i + 1);

            $sheet->setCellValue($col_name . '1', $excel_component[$i - 3]);
        }

        $count = 2;

        foreach ($grouped_data as $row) {
            for ($j = 0; $j < count($excel_fields); $j++) {
                if (!empty($row[$excel_fields[$j]])) {
                    if ($excel_fields[$j] == 'workorders_profiles_created_at') {
                        $cellData = custom_date('d-m-Y', $row[$excel_fields[$j]]);
                    } else {

                        $cellData = $row[$excel_fields[$j]];
                    }
                } else {
                    $cellData = '';
                }
                $cell_name = Coordinate::stringFromColumnIndex($j + 1);
                $sheet->setCellValue($cell_name . $count, $cellData); // A2(Ref_id)
            }
            $profile_service_name = array();
            foreach ($row['checks_data'] as $datas) {
                $profile_service_name[] = $datas['services_category_code'];
            }
            $check_counts = array_count_values($profile_service_name);
            $position = array();
            $excel_label_value = array();
            foreach ($check_counts as $value => $data) {
                for ($i = 1; $i <= $data; $i++) {
                    $excel_label_value[] = $value . '-' . $i;
                }
            }
            for ($k = 0; $k < count($excel_label_value); $k++) {
                $position[] = $this->position($excel_label_value[$k], $excel_component);
            }
            // print_r($position);
            for ($i = 0; $i < count($excel_component); $i++) {
                $empty_cell = Coordinate::stringFromColumnIndex($i + 4);
                $sheet->setCellValue($empty_cell . $count, 'N/A');
            }


            $check_details = array();
            $status_details = array();
            foreach ($row['checks_data'] as $datas) {
                $check_details[] = $datas['status_name'];
            }

            for ($i = 0; $i < count($position); $i++) {
                $component_cell = Coordinate::stringFromColumnIndex($position[$i]);
                $sheet->setCellValue($component_cell . $count, $check_details[$i]);
            }
            $status_details[] = array(
                'status' => $row['workorders_profiles_status'],
                'updated_at' => $row['workorders_profiles_updated_date']
            );

            $status_position = array();
            $completed_date_position = array();
            $status = array('Final Status');
            for ($i = 0; $i < count($excel_component); $i++) {
                for ($k = 0; $k < count($status); $k++) {
                    if ($status[$k] == $excel_component[$i]) {
                        $status_position[] = $i + 4;
                        $completed_date_position[] = $i + 5;
                    }
                }
            }
            for ($i = 0; $i < count($status_position); $i++) {
                $status_cell = Coordinate::stringFromColumnIndex($status_position[$i]);
                $sheet->setCellValue($status_cell . $count, $status_details[$i]['status']);
            }

            for ($i = 0; $i < count($completed_date_position); $i++) {
                $completed_date_cell = Coordinate::stringFromColumnIndex($completed_date_position[$i]);
                if ($status_details[$i]['status'] == 'Completed') {
                    $completed_date = custom_date('d-m-Y', $status_details[$i]['updated_at']);
                } else {
                    $completed_date = 'N/A';
                }
                $sheet->setCellValue($completed_date_cell . $count, $completed_date);
            }
            $count++;
        }

        $writer = new Xlsx($spreadsheet);
        $dir = 'reports/mis/';
        $file_name = "export_checks_" . date('d_m_Y_h_i_s_A') . ".xlsx";
        create_directory($dir);
        $filePath = $dir . $file_name;
        $writer->save($filePath);
        $res = array(
            'filename' => $file_name,
            'url' => base_url() . $filePath
        );
        $result = array('details' => $res);

        $value = withSuccess($this->lang->line('Report downloaded'), $result);

    } else {
        $value = withErrors($this->lang->line('no_result_found'));
    }
    $this->response($value, REST_Controller::HTTP_OK);
}



function position($value, $labels)
{
    for ($i = 0; $i < count($labels); $i++) {
        if ($value == $labels[$i]) {
            $position = $i + 4;
            return $position;
        }
    }
}

function check_count($data)
{
    $service_name = array();
    foreach ($data as $row) {
        $service_name[] = $row['services_category_code'];
    }
    return $service_name;
}


// INNER JOIN $this->lookups_table on $this->lookups_table.id = $this->workorder_profiles_table.status    


// $this->workorder_profiles_table.email as workorders_profiles_email,
//             $this->lookups_table.l_value as workorders_profiles_status,
//             $this->workorder_profiles_table.updated_at as workorders_profiles_updated_date