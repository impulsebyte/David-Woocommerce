<?php

class products
{

    public static $exists;

    public static function getItemByRef($item)
    {
        if ($item->get_variation_id() > 0) {
            $productPop = new WC_Product_Variation($item['variation_id']);
        } else {
            $productPop = new WC_Product($item['product_id']);
        }

        $reference = PRODUCT_PREFIX . $productPop->get_sku();

        if (trim($reference) == '') {

            $name = explode(" ", $item['name']);
            foreach ($name as $w) {
                $reference .= "_" . mb_substr($w, 0, 3);
            }

            $reference = PRODUCT_PREFIX . $item['product_id'] . ($item->get_variation_id() > 0 ? $item->get_variation_id() : "");
            $reference = (strtoupper($reference));
        }

        $reference = mb_substr($reference, 0, 20);

        $values["company_id"] = COMPANY_ID;
        $values["reference"] = $reference;
        $values["qty"] = "1";
        $values["offset"] = "0";
        $values["exact"] = "1";

        $results = cURL::simple("products/getByReference", $values);

        if (isset($results[0]["product_id"])) {
            $itemID = $results[0]["product_id"];
        } else {
            $itemID = self::insertItem($reference, $item);
        }

        return($itemID);
    }

    public static function insertItem($reference, $item)
    {


        $price = $item->get_subtotal() / $item->get_quantity();
        $taxPerItem = $item->get_subtotal_tax() / $item->get_quantity();
        $taxRate = round(($taxPerItem * 100) / $price);

        $term_list = wp_get_post_terms($item['product_id'], 'product_cat', array('fields' => 'ids'));
        $cat_id = (int) $term_list[0];
        $cat = get_term($cat_id, 'product_cat');

        $catName = $cat->name;

        if ($catName == '')
            $catName = 'WooComerce';

        $stock = get_post_meta($item['product_id'], '_stock', true);

        $values["company_id"] = COMPANY_ID;
        $values["category_id"] = self::getItemCategory($catName);
        $values["type"] = "1";
        $values["name"] = ($item['name']);
        $values["summary"] = "";
        $values["reference"] = $reference;
        $values["price"] = $price;
        $values["unit_id"] = MEASURE_UNIT;
        $values["has_stock"] = 1;
        $values["stock"] = ($stock < 1 ? 0 : $stock);
        $values["exemption_reason"] = EXEMPTION_REASON;

        if ($taxRate <> 0) {
            $values["taxes"] = array();
            $taxId = self::getTaxByVal($taxRate);
            $values["taxes"][0]['tax_id'] = (!empty($taxId) && $taxId > 0 ? $taxId : self::getTaxByVal(23));
            $values["taxes"][0]['value'] = $taxPerItem;
            $values["taxes"][0]['order'] = "1";
            $values["taxes"][0]['cumulative'] = "0";
        }

        $results = cURL::simple("products/insert", ($values));
        if (!isset($results['product_id'])) {
            base::genError("products/insert", $values, $results);
        } else {
            return($results['product_id']);
        }
    }

    public static function getShipByRef($sku, $fullPrice, $taxPrice)
    {

        $reference = PRODUCT_PREFIX . $sku;

        $values["company_id"] = COMPANY_ID;
        $values["reference"] = $reference;
        $values["qty"] = "1";
        $values["offset"] = "0";
        $values["exact"] = "1";

        $results = cURL::simple("products/getByReference", $values);

        if (count($results) > 0) {
            $itemID = $results[0]["product_id"];
        } else {
            $itemID = self::insertShiping($reference, $fullPrice, $taxPrice);
        }
        return($itemID);
    }

    public static function insertShiping($reference, $fullPrice, $taxPrice)
    {

        $values["company_id"] = COMPANY_ID;
        $values["category_id"] = self::getItemCategory("Portes");
        $values["type"] = "1";
        $values["name"] = "Portes";
        $values["summary"] = "Custo de transporte";
        $values["reference"] = $reference;
        $values["price"] = $fullPrice;
        $values["unit_id"] = MEASURE_UNIT;
        $values["has_stock"] = 1;
        $values["stock"] = "1";
        $values["exemption_reason"] = EXEMPTION_REASON_SHIPPING;


        if ($taxPrice > 0) {
            $taxRate = round(( $taxPrice * 100 ) / $fullPrice);
            $taxId = self::getTaxByVal($taxRate);

            $values["taxes"] = array();
            $values["taxes"][0]['tax_id'] = (!empty($taxId) && $taxId > 0 ? $taxId : self::getTaxByVal(23));
            $values["taxes"][0]['value'] = $taxRate;
            $values["taxes"][0]['order'] = "1";
            $values["taxes"][0]['cumulative'] = "0";
        }

        $results = cURL::simple("products/insert", ($values));

        if (!isset($results['product_id'])) {
            base::genError("products/insert", $values, $results);
        } else {
            return($results['product_id']);
        }
    }

    public static function getItemCategory($category)
    {

        if ($category <> '') {
            $name = $category;
        } else {
            $name = "Sem Categoria";
        }

        $resultsCategory = self::getCategoryByName($name);
        if ($resultsCategory) {
            return($resultsCategory);
        } else {
            return(self::insertCategory($name) );
        }
    }

    public static function getCategoryByName($name, $parentID = 0)
    {
        if ($name == '') {
            $name = "Sem categoria";
        }

        $categorias = self::getCategoriasAll($parentID);

        if ($categorias) {
            foreach ($categorias as $categoria) {

                if (preg_replace('/[^\w\d ]/', '', (strtolower(htmlspecialchars_decode($categoria['name'])))) == preg_replace('/[^\w\d ]/', '', (htmlspecialchars_decode(strtolower($name))))) {
                    self::$exists = $categoria['category_id'];
                    break;
                }

                if ($categoria['num_categories'] > 0)
                    self::getCategoryByName($name, $categoria['category_id']);
            }
        }else {
            return(FALSE);
        }
        if (empty(self::$exists) OR self::$exists == '' OR ! self::$exists) {
            return(FALSE);
        } else
            return (self::$exists);
    }

    public static function getCategoriasAll($parent = 0)
    {
        $values['company_id'] = COMPANY_ID;
        $values['parent_id'] = $parent;
        $results = cURL::simple("productCategories/getAll", $values);
        return($results);
    }

    public static function insertCategory($name)
    {
        $values["company_id"] = COMPANY_ID;
        $values["parent_id"] = "0";
        $values["name"] = $name;
        $values["description"] = "";
        $results = cURL::simple("productCategories/insert", $values);
        return($results["category_id"]);
    }

    public static function getTaxByVal($val, $level = 0)
    {
        $taxID = "";
        $values['company_id'] = COMPANY_ID;
        $results = cURL::simple("taxes/getAll", $values);
        foreach ($results as $tax) {
            switch ($level) {
                case 0:
                    if (round($tax['value'], 2) == (round($val, 2))) {
                        $taxID = $tax['tax_id'];
                    }
                    break;

                case 1:
                    if (round($tax['value']) == (round($val))) {
                        $taxID = $tax['tax_id'];
                    }
                    break;
                case 2:
                    $taxID = $val;
                    break;
            }
        }

        return($taxID);
    }

}

?>