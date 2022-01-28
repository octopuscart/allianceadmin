<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    function edit_table_information($tableName, $id) {
        $this->User_model->tracking_data_insert($tableName, $id, 'update');
        $this->db->update($tableName, $id);
    }

    public function query_exe($query) {
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $data[] = $row;
            }
            return $data; //format the array into json data
        }
    }

    function delete_table_information($tableName, $columnName, $id) {
        $this->db->where($columnName, $id);
        $this->db->delete($tableName);
    }

    ///*******  Get data for deepth of the array  ********///

    function get_children($id, $container) {
        $this->db->where('id', $id);
        $query = $this->db->get('category');
        $category = $query->result_array()[0];
        $this->db->where('parent_id', $id);
        $query = $this->db->get('category');
        if ($query->num_rows() > 0) {
            $childrens = $query->result_array();

            $category['children'] = $query->result_array();

            foreach ($query->result_array() as $row) {
                $pid = $row['id'];
                $this->get_children($pid, $container);
            }
            return $category;
        } else {
            return $category;
        }
    }

    function getparent($id, $texts) {
        $this->db->where('id', $id);
        $query = $this->db->get('category');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                array_push($texts, $row);
                $texts = $this->getparent($row['parent_id'], $texts);
            }
            return $texts;
        } else {
            return $texts; //format the array into json data
        }
    }

    function parent_get($id) {
        $catarray = $this->getparent($id, []);
        array_reverse($catarray);
        $catarray = array_reverse($catarray, $preserve_keys = FALSE);
        $catcontain = array();
        foreach ($catarray as $key => $value) {
            array_push($catcontain, $value['category_name']);
        }
        $catstring = implode("->", $catcontain);
        return array('category_string' => $catstring, "category_array" => $catarray);
    }

    function child($id) {
        $this->db->where('parent_id', $id);
        $query = $this->db->get('category');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $cat[] = $row;
                $cat[$row['id']] = $this->child($row['id']);
                $cat[] = $row;
            }
            return $cat; //format the array into json data
        }
    }

    function singleProductAttrs($product_id) {
        $query = "SELECT pa.attribute, pa.product_id, pa.attribute_value_id, cav.attribute_value FROM product_attribute as pa 
join category_attribute_value as cav on cav.id = pa.attribute_value_id
where pa.product_id = $product_id group by attribute_value_id";
        $product_attr_value = $this->query_exe($query);
        $arrayattr = [];
        foreach ($product_attr_value as $key => $value) {
            $attrk = $value['attribute'];
            $attrv = $value['attribute_value'];
            array_push($arrayattr, $attrk . '-' . $attrv);
        }
        return implode(", ", $arrayattr);
    }

    function product_attribute_list($product_id) {
        $this->db->where('product_id', $product_id);
        $this->db->group_by('attribute_value_id');
        $query = $this->db->get('product_attribute');
        $atterarray = array();
        if ($query->num_rows() > 0) {
            $attrs = $query->result_array();
            foreach ($attrs as $key => $value) {
                $atterarray[$value['attribute_id']] = $value;
            }
            return $atterarray;
        } else {
            return array();
        }
    }

    function productAttributes($product_id) {
        $pquery = "SELECT pa.attribute, cav.attribute_value, cav.additional_value FROM product_attribute as pa
      join category_attribute_value as cav on cav.id = pa.attribute_value_id
      where pa.product_id = $product_id";
        $attr_products = $this->query_exe($pquery);
        return $attr_products;
    }

    function variant_product_attr($product_id) {
        $queryr = "SELECT pa.attribute_id, pa.attribute, pa.product_id, pa.attribute_value_id, cav.attribute_value FROM product_attribute as pa 
join category_attribute_value as cav on cav.id = pa.attribute_value_id 
where pa.product_id=$product_id ";
        $query = $this->db->query($queryr);
        return $query->result_array();
    }

    function category_attribute_list($id) {
        $this->db->where('attribute_id', $id);
        $this->db->group_by('attribute_value');
        $query = $this->db->get('category_attribute_value');
        if ($query->num_rows() > 0) {
            $attrs = $query->result_array();
            return $attrs;
        } else {
            return array();
        }
    }

    function category_items_prices_id($category_items_id) {

        $queryr = "SELECT cip.price, ci.item_name, cip.id FROM custome_items_price as cip
                       join custome_items as ci on ci.id = cip.item_id
                       where cip.category_items_id = $category_items_id";
        $query = $this->db->query($queryr);
        $category_items_price_array = $query->result();
        return $category_items_price_array;
    }

    function category_items_prices() {
        $query = $this->db->get('category_items');
        $category_items = $query->result();
        $category_items_return = array();
        foreach ($category_items as $citkey => $citvalue) {
            $category_items_id = $citvalue->id;
            $category_items_price_array = $this->category_items_prices_id($category_items_id);
            $citvalue->prices = $category_items_price_array;
            array_push($category_items_return, $citvalue);
        }
        return $category_items_return;
    }

///udpate after 16-02-2019
    function stringCategories($category_id) {
        $this->db->where('parent_id', $category_id);
        $query = $this->db->get('category');
        $category = $query->result_array();
        $container = "";
        foreach ($category as $ckey => $cvalue) {
            $container .= $this->stringCategories($cvalue['id']);
            $container .= ", " . $cvalue['id'];
        }
        return $container;
    }

    function getUserPoints($user_id) {
        $query = "
            select id, date, points, points_type, remark, paid_amount, payment_status, product_id from (
SELECT id, date, points, status as remark,  'Debit' as points_type, '' as product_id, paid_amount, payment_status FROM product_rewards_request where user_id = $user_id
union all
 SELECT id, date, points, serial_no as remark,'Credit' as points_type, product_id, '' as paid_amount, '' as payment_status FROM product_rewards WHERE plumber_id  = $user_id
    ) as a order by date desc
                ";

        $queryrow = $this->db->query($query);
        $pointhistory = $queryrow->result_array();
        $creditList = [];
        $debititList = [];
        $creditsum = 0;
        $debitsum = 0;
        $finallist = [];
        foreach ($pointhistory as $key => $pvalue) {
            $this->db->where("id", $pvalue["product_id"]);
            $query = $this->db->get('products');
            $productobj = $query->row_array();

            if ($pvalue['points_type'] == "Credit") {
                array_push($creditList, $pvalue);
                $creditsum += $pvalue["points"];
                $pvalue["product"] = $productobj ? $productobj["title"] : "";
            } else {
                $debitsum += $pvalue["points"];
                array_push($debititList, $pvalue);
                if ($pvalue["remark"] == "Paid") {
                    $amount = $pvalue["paid_amount"];
                    $pvalue["product"] = "Payment ($amount INR) has been credited.";
                } else {
                    $pvalue["product"] = "Points have been redeemed.";
                }
            }
            array_push($finallist, $pvalue);
        }

        $this->db->select("sum(points) as total, sum(paid_amount) as paid");
        $this->db->where("user_id", $user_id);
        $query = $this->db->get("product_rewards_request");
        $rcarddata = $query->row_array();
        return array("pointlist" => $finallist, "paid" => $rcarddata["paid"] ? $rcarddata["paid"] : 0, "credit" => $creditsum, "debitsum" => $debitsum, "totalremain" => ($creditsum - $debitsum));
    }

    function getUserPoints2($user_id) {
        $this->db->where('plumber_id', $user_id);
        $this->db->order_by("id desc");
        $query = $this->db->get('product_rewards');
        $userpointdata = $query->result_array();
        $creditList = [];
        $debititList = [];
        $creditsum = 0;
        $debitsum = 0;
        $finallist = [];
        foreach ($userpointdata as $pkey => $pvalue) {
            $this->db->where("id", $pvalue["product_id"]);
            $query = $this->db->get('products');
            $productobj = $query->row_array();

            if ($pvalue['points_type'] == "Credit") {
                array_push($creditList, $pvalue);
                $creditsum += $pvalue["points"];
                $pvalue["product"] = $productobj ? $productobj["title"] : "";
            } else {
                $debitsum += $pvalue["points"];
                array_push($debititList, $pvalue);
                $pvalue["product"] = "Points have been redeemed.";
            }
            array_push($finallist, $pvalue);
        }
        $this->db->select("sum(points) as total, sum(paid_amount) as paid");
        $this->db->where("user_id", $user_id);
        $query = $this->db->get("product_rewards_request");
        $rcarddata = $query->row_array();

        return array("pointlist" => $finallist, "paid" => $rcarddata["paid"] ? $rcarddata["paid"] : 0, "credit" => $creditsum, "debitsum" => $debitsum, "totalremain" => ($creditsum - $debitsum));
    }

}
