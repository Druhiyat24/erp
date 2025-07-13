<?PHP
    if (empty($_SESSION['username'])) { 
        header("location:../../"); 
    }
    if (!isset($_SESSION['username'])) { 
        header("location:../../"); 
    }
    $nm_company=flookup("company","mastercompany","company<>''");
    $st_company=flookup("status_company","mastercompany","company<>''");
    if (isset($_SESSION['username'])) { 
        $user=$_SESSION['username']; 
    } 
    else { 
        header("location:../../"); 
    }
    if ($st_company=="KITE") { 
        $captupl="Upload Data"; 
    } 
    else { 
        $captupl="Upload Data Dari ModulTPB"; 
    }
    $rsU=mysql_fetch_array(mysql_query("select * from userpassword where username='$user'"));
?>

<style type="text/css">
    .dropdown-submenu {
        position: relative;
    }
    .dropdown-submenu>.dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -6px;
        margin-left: -1px;
        -webkit-border-radius: 0 6px 6px 6px;
        -moz-border-radius: 0 6px 6px;
        border-radius: 0 6px 6px 6px;
    }
    .dropdown-submenu:hover>.dropdown-menu {
        display: block;
    }
    .dropdown-submenu>a:after {
        display: block;
        content: " ";
        float: right;
        width: 0;
        height: 0;
        border-color: transparent;
        border-style: solid;
        border-width: 5px 0 5px 5px;
        border-left-color: #ccc;
        margin-top: 5px;
        margin-right: -10px;
    }
    .dropdown-submenu:hover>a:after {
        border-left-color: #fff;
    }
    .dropdown-submenu.pull-left {
        float: none;
    }
    .dropdown-submenu.pull-left>.dropdown-menu {
        left: -100%;
        margin-left: 10px;
        -webkit-border-radius: 6px 0 6px 6px;
        -moz-border-radius: 6px 0 6px 6px;
        border-radius: 6px 0 6px 6px;
    }
</style>


<nav class="navbar navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <a href="?mod=1" class="navbar-brand"><b><?PHP echo $nm_company;?></b></a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <i class="fa fa-bars"></i>
            </button>
        </div>

        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="" class="dropdown-toggle" data-toggle="dropdown">Process<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">Cutting</a>
                            <ul class="dropdown-menu">
                                <?php
                                $akses = $rsU['mark_entry_page'];
                                if ($akses=="1") { echo "<li><a href='?mod=PW'>Marker Entry</a></li>"; }
                                $akses = $rsU['spreading_page'];
                                if ($akses=="1") { echo "<li><a href='?mod=SpreadingReport'>Spreading Report</a></li>"; }
                                $akses = $rsU['m_roll_page'];
                                if ($akses=="1") { echo "<li><a href='?mod=mRollListWs'>Management Roll</a></li>"; }
                                ?>
                                <li class="dropdown-submenu">
                                    <a href="#">Cutting</a>
                                    <ul class="dropdown-menu">
                                        <?php 
                                        $akses = $rsU['cut_out_page'];
                                        if ($akses=="1") { echo "<li><a href='?mod=3WP'>Cutting Output</a></li>"; }
                                        $akses = $rsU['cut_number_page'];
                                        if ($akses=="1") { echo "<li><a href='?mod=numCut'>Cutting Numbering</a></li>"; }
                                        $akses = $rsU['cut_qc_page'];
                                        if ($akses=="1") { echo "<li><a href='?mod=cutQC'>Cutting QC</a></li>"; }
                                        ?>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#">Secondary</a>
                                    <ul class="dropdown-menu">
                                        <?php 
                                        $akses = $rsU['sec_in_page'];
                                        if ($akses=="1") { echo "<li><a href='?mod=4WP'>Secondary Input</a></li>"; }
                                        $akses = $rsU['sec_out_page'];
                                        if ($akses=="1") { echo "<li><a href='?mod=5WP'>Secondary Output</a></li>"; }
                                        $akses = $rsU['sec_qc_page'];
                                        if ($akses=="1") { echo "<li><a href='?mod=secQC'>Secondary QC</a></li>"; }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                                $akses = $rsU['dc_join_page'];
                                if ($akses=="1") { echo "<li><a href='?mod=6WP'>Distribution Center Join</a></li>"; }
                                $akses = $rsU['dc_set_page'];
                                if ($akses=="1") { echo "<li><a href='?mod=7WP'>Distribution Center Set</a></li>"; }
                                ?>
                            </ul>
                        <li>

                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">Sewing</span></a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-submenu">
                                    <a href="#">Sewing</a>
                                    <ul class="dropdown-menu">
                                        <?php 
                                        $akses = $rsU['PROD_SEW_IN_P'];
                                        if ($akses=="1") { echo "<li><a href='?mod=8WP'>Sewing Input</a></li>"; }
                                         $akses = $rsU['PROD_SEW_OUT_P'];
										if ($akses=="1") { echo "<li><a href='?mod=9WP'>Sewing Output</a></li>"; }
                                        ?>
                                    </ul>
                                </li>
                                <li class="dropdown-submenu">
                                    <a href="#">QC</a>
                                    <ul class="dropdown-menu">
                                        <?php 
                                        $akses = $rsU['PROD_SEW_QC_IN_P'];
                                        if ($akses=="1") { echo "<li><a href='?mod=10WP'>QC Input</a></li>"; }
										 $akses = $rsU['PROD_SEW_QC_OUT_P'];
									   if ($akses=="1") { echo "<li><a href='?mod=11WP'>QC Output</a></li>"; }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                                $akses = $rsU['PROD_STE_P'];
                                if ($akses=="1") { echo "<li><a href='?mod=11L'>Steam</a></li>"; }
                                ?>
                                <li class="dropdown-submenu">
                                    <a href="#">QC Final</a>
                                    <ul class="dropdown-menu">
                                        <?php 
                                        $akses = $rsU['PROD_SEW_QC_FINAL_IN_P'];
                                        if ($akses=="1") { echo "<li><a href='?mod=QC_F_I_Page'>QC Final Input</a></li>"; }
                                        $akses = $rsU['PROD_SEW_QC_FINAL_OUT_P'];
										if ($akses=="1") { echo "<li><a href='?mod=QC_F_O_Page'>QC Final Output</a></li>"; }
                                        ?>
                                    </ul>
                                </li>
                            </ul>
                        <li>

                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">Finishing</span></a>
                            <ul class="dropdown-menu">
                                <?php
                                $akses = $rsU['PROD_PACK_P'];
                                if ($akses=="1") { echo "<li><a href='?mod=Pack_Page'>Packing</a></li>"; }
                                $akses = $rsU['PROD_MET_DET_P'];
								if ($akses=="1") { echo "<li><a href='?mod=Met_Dec_Page'>Metal Detector</a></li>"; }
                                 $akses = $rsU['PROD_QC_AUD_BUY_P'];
								if ($akses=="1") { echo "<li><a href='?mod=QC_A_Page'>QC Final Audit Buyer</a></li>"; }
                                 $akses = $rsU['PROD_FG_P'];
								if ($akses=="1") { echo "<li><a href='?mod=FG_Page'>Finish Good</a></li>"; }
                                 $akses = $rsU['PROD_SHP_P'];
								if ($akses=="1") { echo "<li><a href='?mod=SHP_Page'>Shipping</a></li>"; }
                                ?>
                            </ul>
                        <li>


                        <?php 
                        // $akses = $rsU['cutting_output'];
                        // if ($akses=="1") { echo "<li><a href='?mod=PW'>Marker Entry</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=SpreadingReport'>Spreading Report</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=PW3'>Management Roll</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=2WP'>Cutting Input</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=3WP'>Cutting Output</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=3WP'>Numbering</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=4WP'>Secondary Input</a></li>"; } 
                        // if ($akses=="1") { echo "<li><a href='?mod=5WP'>Secondary Output</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=6WP'>DC Input</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=7WP'>DC Output</a></li>"; }


                        // $akses = $rsU['mfg_output'];
                        // if ($akses=="1") { echo "<li><a href='?mod=4WP'>Secondary Process Input</a></li>"; } 
                        // if ($akses=="1") { echo "<li><a href='?mod=5WP'>Secondary Process Output</a></li>"; }
                        // $akses = $rsU['dc_out'];
                        // if ($akses=="1") { echo "<li><a href='?mod=6WP'>DC Input</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=7WP'>DC Output</a></li>"; }
                        // $akses = $rsU['sewing_output'];
                        // if ($akses=="1") { echo "<li><a href='?mod=8WP'>Sewing Input</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=9WP'>Sewing Output</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=8L'>Sewing Input</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=4L'>Sewing Output</a></li>"; }
                        // $akses = $rsU['qc_output'];
                        // if ($akses=="1") { echo "<li><a href='?mod=10WP'>QC Input</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=11WP'>QC Output</a></li>"; }

                        // if ($akses=="1") { echo "<li><a href='?mod=QcInputPage'>QC Input</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=5L'>$caption[7] Output</a></li>"; }
                        // $akses = $rsU['steam_out'];
                        // if ($akses=="1") { echo "<li><a href='?mod=11L'>Steam Input</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=11L'>Steam Output</a></li>"; }
                        // $akses = $rsU['pack_output'];
                        // if ($akses=="1") { echo "<li><a href='?mod=6L'>Packing Input</a></li>"; }
                        // if ($akses=="1") { echo "<li><a href='?mod=6L'>Packing Output</a></li>"; }
                        ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="" class="dropdown-toggle" data-toggle="dropdown">Report<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <?php 
                        echo "<li><a href='?mod=7s'>Production Status Summary</a></li>";
                        echo "<li><a href='?mod=7'>Production Status Detail</a></li>";
                        ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="" class="dropdown-toggle" data-toggle="dropdown">Tools<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <?php
                        $akses = $rsU['unlock_prod'];
                        if ($akses=="1") { 
                            echo "<li><a href='?mod=9'>Unlock Production Output</a></li>"; 
                        } 
                        ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="../">Main Menu</a>
                </li>
            </ul>
        </div>
    </div>
</nav>