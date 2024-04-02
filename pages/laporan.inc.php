<?php
/**
 *
 * Copyright (C) 2007,2008  Arie Nugraha (dicarve@yahoo.com)
 * Modified for Excel output (C) 2010 by Wardiyono (wynerst@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

/* Report By Titles */

// key to authenticate
// define('INDEX_AUTH', '1');

// main system configuration
// require '../../../../sysconfig.inc.php';
// IP based access limitation
require LIB.'ip_based_access.inc.php';
do_checkIP('smc');
do_checkIP('smc-reporting');
// start the session
require SB.'admin/default/session.inc.php';
require SB.'admin/default/session_check.inc.php';
// privileges checking
$can_read = utility::havePrivilege('reporting', 'r');
$can_write = utility::havePrivilege('reporting', 'w');

if (!$can_read) {
    die('<div class="errorBox">'.__('You don\'t have enough privileges to access this area!').'</div>');
}

require SIMBIO.'simbio_GUI/table/simbio_table.inc.php';
require SIMBIO.'simbio_GUI/paging/simbio_paging.inc.php';
require SIMBIO.'simbio_GUI/form_maker/simbio_form_element.inc.php';
require SIMBIO.'simbio_DB/datagrid/simbio_dbgrid.inc.php';
require MDLBS.'reporting/report_dbgrid.inc.php';

$page_title = 'Titles Report';
$reportView = false;
$num_recs_show = 20;
if (isset($_GET['reportView'])) {
    $reportView = true;
}

if (!$reportView) {
$id = str_replace(['"','\'','`'], '', strip_tags($_GET['id']));
$mod = str_replace(['"','\'','`'], '', strip_tags($_GET['mod']));
?>
<!-- filter -->
<div class="per_title">
    <h2><?php echo __('Laporan Usul Buku'); ?></h2>
</div>
<div class="infoBox">
    <?php echo __('Report Filter'); ?>
</div>
<div class="sub_section">
    <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" target="reportView">
        <input type="hidden" name="id" value="<?= $id ?>"/>
        <input type="hidden" name="mod" value="<?= $mod ?>"/>
        <div id="filterForm">
            <div class="form-group divRow">
                <label><?php echo __('Title/ISBN'); ?></label>
                <?php echo simbio_form_element::textField('text', 'title', '', 'class="form-control col-4"'); ?>
            </div>
            <div class="form-group divRow">
                <label><?php echo __('Author'); ?></label>
                <?php echo simbio_form_element::textField('text', 'author', '', 'class="form-control col-4"'); ?>
            </div>
            <div class="form-group divRow">
                <label><?php echo __('Identitas'); ?></label>
                <?php echo simbio_form_element::textField('text', 'identitas', '', 'class="form-control col-4"'); ?>
            </div>
            <div class="form-group divRow">
                <label><?php echo __('Tahun Terbit'); ?></label>
                <?php echo simbio_form_element::textField('text', 'tahun_terbit', '', 'class="form-control col-4"'); ?>
            </div>
            <div class="form-group divRow">
                <label><?php echo __('Input Date'); ?></label>
                <div class="divRowContent">
                    <div id="range">
                        <input type="text" name="inputDateStart">
                        <span><?= __('to') ?></span>
                        <input type="text" name="inputDateEnd">
                    </div>
                </div>
            </div>
            <div class="form-group divRow">
                <label><?php echo __('Publish year'); ?></label>
                <?php echo simbio_form_element::textField('text', 'publishYear', '', 'class="form-control col-4"'); ?>
            </div>
            <div class="form-group divRow">
                <label><?php echo __('Record each page'); ?></label>
                <input type="text" name="recsEachPage" size="3" maxlength="3" class="form-control col-1" value="<?php echo $num_recs_show; ?>" /><small class="text-muted"><?php echo __('Set between 20 and 200'); ?></small>
            </div>
        </div>
        <input type="button" name="moreFilter" class="btn btn-default" value="<?php echo __('Show More Filter Options'); ?>" />
        <input type="submit" name="applyFilter" class="btn btn-primary" value="<?php echo __('Apply Filter'); ?>" />
        <input type="hidden" name="reportView" value="true" />
    </form>
</div>
<script>
    $(document).ready(function(){
        const elem = document.getElementById('range');
        const dateRangePicker = new DateRangePicker(elem, {
            language: '<?= substr($sysconf['default_lang'], 0,2) ?>',
            format: 'yyyy-mm-dd',
        });
    })
</script>
<!-- filter end -->
<div class="paging-area"><div class="pt-3 pr-3" id="pagingBox"></div></div>
<iframe name="reportView" id="reportView" src="<?php echo $_SERVER['PHP_SELF'].'?' . $_SERVER['QUERY_STRING'] . '&reportView=true'; ?>" frameborder="0" style="width: 100%; height: 500px;"></iframe>
<?php
} else {
    ob_start();
    // create datagrid
    $reportgrid = new report_datagrid();
    $reportgrid->table_attr = 'class="s-table table table-sm table-bordered"';
    $reportgrid->setSQLColumn('*');
    $reportgrid->setSQLorder('identitas ASC');
    $reportgrid->invisible_fields = array(0);

    // is there any search
    $criteria = 'identitas IS NOT NULL ';

    // table spec
    $table_spec = 'usul_buku';

    if (isset($_GET['title']) && !empty($_GET['title'])) {
        $title = $dbs->escape_string($_GET['title']);
        $criteria .= ' and `judul` like \'%'.$title.'%\'';
    }

    if (isset($_GET['author']) && !empty($_GET['author'])) {
        $author = $dbs->escape_string($_GET['author']);
        $criteria .= ' and `pengarang` like \'%'.$author.'%\'';
    }

    if (isset($_GET['identitas']) && !empty($_GET['identitas'])) {
        $identitas = $dbs->escape_string($_GET['identitas']);
        $criteria .= ' and `identitas` like \'%'.$identitas.'%\'';
    }

    if (isset($_GET['tahun_terbit']) && !empty($_GET['tahun_terbit'])) {
        $tahunterbit = $dbs->escape_string($_GET['tahun_terbit']);
        $criteria .= ' and `tahunterbit` like \'%'.$tahunterbit.'%\'';
    }

    if (isset($_GET['inputDateStart']) AND !empty($_GET['inputDateStart']) && isset($_GET['inputDateEnd']) AND !empty($_GET['inputDateEnd'])) {
        $inputDateStart = $dbs->escape_string(trim($_GET['inputDateStart']));
        $inputDateEnd = $dbs->escape_string(trim($_GET['inputDateEnd']));
        $criteria .= ' AND (date(created_at) >= \'' . $inputDateStart . '\' AND date(created_at) <= \'' . $inputDateEnd . '\')';
    }

    // set group by
    $reportgrid->sql_group_by = 'identitas';
    $reportgrid->setSQLCriteria($criteria);

    // callback function to show title and authors
    function showTitleAuthors($obj_db, $array_data)
    {
        // author name query
        $_biblio_q = $obj_db->query('SELECT b.title, a.author_name FROM biblio AS b
            LEFT JOIN biblio_author AS ba ON b.biblio_id=ba.biblio_id
            LEFT JOIN mst_author AS a ON ba.author_id=a.author_id
            WHERE b.biblio_id='.$array_data[0]);
        $_authors = '';
        while ($_biblio_d = $_biblio_q->fetch_row()) {
            $_title = $_biblio_d[0];
            $_authors .= $_biblio_d[1].' - ';
        }
        $_authors = substr_replace($_authors, '', -3);
        $_output = $_title.'<br /><i>'.$_authors.'</i>'."\n";
        return $_output;
    }
    // modify column value
    // $reportgrid->modifyColumnContent(1, 'callback{showTitleAuthors}');

    // show spreadsheet export button
    $reportgrid->show_spreadsheet_export = true;

    $reportgrid->spreadsheet_export_btn = '<a href="' . MWB . 'reporting/spreadsheet.php" class="s-btn btn btn-default">'.__('Export to spreadsheet format').'</a>';

    // put the result into variables
    echo $reportgrid->createDataGrid($dbs, $table_spec, $num_recs_show);

    echo '<script type="text/javascript">'."\n";
    echo 'parent.$(\'#pagingBox\').html(\''.str_replace(array("\n", "\r", "\t"), '', $reportgrid->paging_set).'\');'."\n";
    echo '</script>';

    $xlsquery = 'SELECT * FROM '. $table_spec . ' WHERE '. $criteria . ' group by identitas';
        // echo $xlsquery;
    unset($_SESSION['xlsdata']);
    $_SESSION['xlsquery'] = $xlsquery;
    $_SESSION['tblout'] = "laporan_usul_buku";
    $content = ob_get_clean();
    // include the page template
    require SB.'/admin/'.$sysconf['admin_template']['dir'].'/printed_page_tpl.php';
}
