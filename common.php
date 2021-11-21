<?php
    class awcbf_write_wptable {

        private $rows = [];

        public function add_row($th, $td, $desc='') {
            $this->rows[$th] = [$td, $desc];
        }

        public function get_html() {
            $str = '';
            $str .= '<table class="form-table"><tbody>';
            foreach($this->rows as $th => $val)
                $str .= '<tr><th>'.$th.'</th><td>'.$val[0].($val[1] ? '<p>'.$val[1].'</p>' : '').'</td></tr>';
            $str .= '</tbody></table>';
            return $str;
        }

    }