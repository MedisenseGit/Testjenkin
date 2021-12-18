<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];

include('functions.php');
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");

					
	$patient_tab = mysqlSelect("*","pharma_customer","md5(pharma_customer_id)='".$_GET['p']."'","","","","");
	if($patient_tab[0]['pharma_cust_gender']=="1"){
		$gender="Male";
	}
	else if($patient_tab[0]['pharma_cust_gender']=="2"){
		$gender="Female";
	}
	$cust_episode = mysqlSelect("*","pharma_referrals","patient_id='".$patient_tab[0]['patient_id']."'","pr_id DESC ","","","");

	$patient_id = $patient_tab[0]['pharma_customer_id'];

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Patient Profile</title>

   <?php include_once('support.php'); ?>
	<!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<!-- orris -->
    <link href="../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

<script language="JavaScript" src="js/status_validationJs.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css">
<script type="text/javascript">
$(function() 
{
 $( "#coding_language" ).autocomplete({
  source: 'get_icd.php'
 });
});
</script>
</head>

<body>

    <div id="wrapper">

   
         <?php include_once('sidemenu.php'); ?>
    

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-3">
                    <h2>Customer Profile</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        <li class="active">
                            <strong>Customer Profile</strong>
                        </li>
                    </ol>
                </div>
				 <div class="col-lg-7 mgTop">
					<div class="search-form">
                                <form action="add_details.php" method="post">
                                    <div class="input-group">
				
                                       <input type="text" placeholder="Search Customer" name="search" value="" class="form-control input-lg typeahead_1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" name="cmdSearch" type="submit">
                                                Search
                                            </button>
                                        </div>
                                    </div>

                                </form>
                    </div>            
			   </div>
                <!--<div class="col-lg-2 mgTop">
					<a href="My-Patients"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-arrow-left"></i> BACK</button></a>
                                
			   </div>-->
            </div>
			<div class="row m-t">
				<div class="col-md-4">

                    <div class="profile-image">
                        <img src="../assets/img/anonymous-profile.png" class="img-circle circle-border m-b-md" alt="profile">
                    </div>
                    <div class="profile-info">
                        <div class="">
                            <div>
                                <h2 class="no-margins">
                                    <?php echo $patient_tab[0]['pharma_customer_name']; ?>
                                </h2>
                                <h4><i class="fa fa-mobile"></i> <?php echo $patient_tab[0]['pharma_customer_phone']; ?></h4>
								 <h4><i class="fa fa-stack-exchange"></i> <?php echo $patient_tab[0]['pharma_customer_email']; ?></h4>
                                <small>
                                    
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <table class="table small m-b-xs">
                        <tbody>
                        <tr>
                            <td>
                                <strong>Gender</strong> <?php echo $gender; ?>
                            </td>
						</tr>
						<tr>	
                            <td>
                                <strong>Age</strong> <?php echo $patient_tab[0]['pharma_cust_age']; ?>
                            </td>

                        </tr>
						<tr>
							<td>
                                <strong>Address</strong> <?php echo $patient_tab[0]['pharma_cust_address'].", ".$patient_tab[0]['pharma_cust_city'].", ".$patient_tab[0]['pharma_cust_state']; ?>
                            </td>
						
						</tr>
                       
                        </tbody>
                    </table>
                </div>
			
			</div>
			<div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInUp">

                    <div class="fh-breadcrumb">

                <div class="fh-column">
                    <div class="full-height-scroll">
                        <ul class="list-group elements-list">
                            <li class="list-group-item">
                                <a data-toggle="tab" href="#tab-1">
                                    <small class="pull-right text-muted"> 16.02.2015</small>
                                    <strong>Ann Smith</strong>
                                    <div class="small m-t-xs">
                                        <p>
                                            Survived not only five centuries, but also the leap scrambled it to make.
                                        </p>
                                        <p class="m-b-none">
                                            <i class="fa fa-map-marker"></i> Riviera State 32/106
                                        </p>
                                    </div>
                                </a>
                            </li>
                            <li class="list-group-item active">
                                <a data-toggle="tab" href="#tab-2">
                                    <small class="pull-right text-muted"> 11.10.2015</small>
                                    <strong>Paul Morgan</strong>
                                    <div class="small m-t-xs">
                                        <p class="m-b-xs">
                                            There are many variations of passages of Lorem Ipsum.
                                            <br/>
                                        </p>
                                        <p class="m-b-none">

                                            <span class="label pull-right label-primary">SPECIAL</span>
                                            <i class="fa fa-map-marker"></i> California 10F/32
                                        </p>
                                    </div>
                                </a>
                            </li>
                                                      
                        </ul>

                    </div>
                </div>

                <div class="full-height">
                    <div class="full-height-scroll white-bg border-left">

                        <div class="element-detail-box">

                            <div class="tab-content">
                                <div id="tab-1" class="tab-pane">

                                    <div class="pull-right">
                                        <div class="tooltip-demo">
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="left" title="Plug this message"><i class="fa fa-plug"></i> Plug it</button>
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="Mark as read"><i class="fa fa-eye"></i> </button>
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mark as important"><i class="fa fa-exclamation"></i> </button>
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Move to trash"><i class="fa fa-trash-o"></i> </button>

                                        </div>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fa fa-clock-o"></i> Friday, 12 April 2014, 12:32 am
                                    </div>

                                    <h1>
                                        Their separate existence is a myth
                                    </h1>

                                    <p>
                                        The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is.
                                    </p>
                                    <p>
                                        The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ in their grammar, their pronunciation and their most common words.
                                    </p>
                                    <p>
                                        The bedding was hardly able to cover it and seemed ready to slide off any moment. His many legs, pitifully thin compared with the size of the rest of him, waved about helplessly as he looked. "What's happened to me?" he thought. It wasn't a dream. His room, a proper human room although a little too small, lay peacefully between its four familiar walls.
                                    </p>

                                    <p>
                                        The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary.
                                    </p>
                                    <p>
                                        The new common language will be more simple and regular than the existing European languages. It will be as simpl.
                                    </p>
                                    <p>
                                        To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words. If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is.
                                    </p>
                                    <p>
                                        The bedding was hardly able to cover it and seemed ready to slide off any moment. His many legs, pitifully thin compared with the size of the rest of him, waved about helplessly as he looked. "What's happened to me?" he thought. It wasn't a dream. His room, a proper human room although a little too small, lay peacefully between its four familiar walls.
                                    </p>
                                    <p>
                                        It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is. The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ in their grammar, their pronunciation and their most common words.
                                    </p>

                                    <p>
                                        The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary.
                                    </p>
                                    <p>
                                        To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words. If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is.
                                    </p>
                                    <p class="small">
                                        <strong>Best regards, Anthony Smith </strong>
                                    </p>

                                    <div class="m-t-lg">
                                        <p>
                                            <span><i class="fa fa-paperclip"></i> 2 attachments - </span>
                                            <a href="#">Download all</a>
                                            |
                                            <a href="#">View all images</a>
                                        </p>

                                        <div class="attachment">
                                            <div class="file-box">
                                                <div class="file">
                                                    <a href="#">
                                                        <span class="corner"></span>

                                                        <div class="icon">
                                                            <i class="fa fa-file"></i>
                                                        </div>
                                                        <div class="file-name">
                                                            Document_2014.doc
                                                            <br>
                                                            <small>Added: Jan 11, 2014</small>
                                                        </div>
                                                    </a>
                                                </div>

                                            </div>
                                            <div class="file-box">
                                                <div class="file">
                                                    <a href="#">
                                                        <span class="corner"></span>

                                                        <div class="icon">
                                                            <i class="fa fa-line-chart"></i>
                                                        </div>
                                                        <div class="file-name">
                                                            Seels_2015.xls
                                                            <br>
                                                            <small>Added: May 13, 2015</small>
                                                        </div>
                                                    </a>
                                                </div>

                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                </div>

                                <div id="tab-2" class="tab-pane active">
                                    <div class="pull-right">
                                        <div class="tooltip-demo">
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="left" title="Plug this message"><i class="fa fa-plug"></i> Plug it</button>
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="Mark as read"><i class="fa fa-eye"></i> </button>
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mark as important"><i class="fa fa-exclamation"></i> </button>
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Move to trash"><i class="fa fa-trash-o"></i> </button>

                                        </div>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fa fa-clock-o"></i> Monday, 21 May 2014, 10:32 am
                                    </div>

                                    <h1>
                                        The European languages - same family.
                                    </h1>

                                    <p>
                                        One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin. He lay on his armour-like back, and if he lifted his head a little he could see his brown belly, slightly domed and divided by arches into stiff sections.
                                    </p>
                                    <p>
                                        The bedding was hardly able to cover it and seemed ready to slide off any moment. His many legs, pitifully thin compared with the size of the rest of him, waved about helplessly as he looked. "What's happened to me?" he thought. It wasn't a dream. His room, a proper human room although a little too small, lay peacefully between its four familiar walls.
                                    </p>

                                    <p>
                                        The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary.
                                    </p>
                                    <p>
                                        To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words. If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is.
                                    </p>
                                    <p>
                                        The bedding was hardly able to cover it and seemed ready to slide off any moment. His many legs, pitifully thin compared with the size of the rest of him, waved about helplessly as he looked. "What's happened to me?" he thought. It wasn't a dream. His room, a proper human room although a little too small, lay peacefully between its four familiar walls.
                                    </p>

                                    <p>
                                        The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary.
                                    </p>
                                    <p>
                                        To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words. If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is.
                                    </p>
                                    <p class="small">
                                        <strong>Best regards, Anthony Smith </strong>
                                    </p>

                                    <div class="m-t-lg">
                                        <p>
                                            <span><i class="fa fa-paperclip"></i> 2 attachments - </span>
                                            <a href="#">Download all</a>
                                            |
                                            <a href="#">View all images</a>
                                        </p>

                                        <div class="attachment">
                                            <div class="file-box">
                                                <div class="file">
                                                    <a href="#">
                                                        <span class="corner"></span>

                                                        <div class="icon">
                                                            <i class="fa fa-file"></i>
                                                        </div>
                                                        <div class="file-name">
                                                            Document_2014.doc
                                                            <br>
                                                            <small>Added: Jan 11, 2014</small>
                                                        </div>
                                                    </a>
                                                </div>

                                            </div>
                                            <div class="file-box">
                                                <div class="file">
                                                    <a href="#">
                                                        <span class="corner"></span>

                                                        <div class="icon">
                                                            <i class="fa fa-line-chart"></i>
                                                        </div>
                                                        <div class="file-name">
                                                            Seels_2015.xls
                                                            <br>
                                                            <small>Added: May 13, 2015</small>
                                                        </div>
                                                    </a>
                                                </div>

                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>

                                <div id="tab-3" class="tab-pane">
                                    <div class="pull-right">
                                        <div class="tooltip-demo">
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="left" title="Plug this message"><i class="fa fa-plug"></i> Plug it</button>
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="Mark as read"><i class="fa fa-eye"></i> </button>
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mark as important"><i class="fa fa-exclamation"></i> </button>
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Move to trash"><i class="fa fa-trash-o"></i> </button>

                                        </div>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fa fa-clock-o"></i> Tuesday, 15 May 2014, 10:32 am
                                    </div>

                                    <h1>
                                        To take a trivial example
                                    </h1>

                                    <p>
                                        But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure? On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to those who fail in their duty through weakness of will, which is the same as saying through shrinking from toil and pain.
                                    </p>
                                    <p>
                                        The bedding was hardly able to cover it and seemed ready to slide off any moment. His many legs, pitifully thin compared with the size of the rest of him, waved about helplessly as he looked. "What's happened to me?" he thought. It wasn't a dream. His room, a proper human room although a little too small, lay peacefully between its four familiar walls.
                                    </p>

                                    <p>
                                        he wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains.But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and.
                                    </p>
                                    <p>
                                        To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words. If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is.
                                    </p>

                                    <p>
                                        The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary.
                                    </p>
                                    <p>
                                        To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure? On the other hand, we denounce.
                                    </p>
                                    <p class="small">
                                        <strong>Best regards, Anthony Smith </strong>
                                    </p>

                                    <div class="m-t-lg">
                                        <p>
                                            <span><i class="fa fa-paperclip"></i> 2 attachments - </span>
                                            <a href="#">Download all</a>
                                            |
                                            <a href="#">View all images</a>
                                        </p>

                                        <div class="attachment">
                                            <div class="file-box">
                                                <div class="file">
                                                    <a href="#">
                                                        <span class="corner"></span>

                                                        <div class="icon">
                                                            <i class="fa fa-file"></i>
                                                        </div>
                                                        <div class="file-name">
                                                            Document_2014.doc
                                                            <br>
                                                            <small>Added: Jan 11, 2014</small>
                                                        </div>
                                                    </a>
                                                </div>

                                            </div>
                                            <div class="file-box">
                                                <div class="file">
                                                    <a href="#">
                                                        <span class="corner"></span>

                                                        <div class="icon">
                                                            <i class="fa fa-line-chart"></i>
                                                        </div>
                                                        <div class="file-name">
                                                            Seels_2015.xls
                                                            <br>
                                                            <small>Added: May 13, 2015</small>
                                                        </div>
                                                    </a>
                                                </div>

                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab-4" class="tab-pane">
                                    <div class="pull-right">
                                        <div class="tooltip-demo">
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="left" title="Plug this message"><i class="fa fa-plug"></i> Plug it</button>
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="Mark as read"><i class="fa fa-eye"></i> </button>
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mark as important"><i class="fa fa-exclamation"></i> </button>
                                            <button class="btn btn-white btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Move to trash"><i class="fa fa-trash-o"></i> </button>

                                        </div>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fa fa-clock-o"></i> Thursday, 27 april 2014, 10:32 am
                                    </div>

                                    <h1>
                                        Gregor Samsa woke from troubled dreams.
                                    </h1>

                                    <p>
                                        His many legs, pitifully thin compared with the size of the rest of him, waved about helplessly as he looked. "What's happened to me?" he thought. It wasn't a dream. His room, a proper human room although a little too small, lay peacefully between its four familiar walls.
                                    </p>
                                    <p>
                                        To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words. If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is.
                                    </p>
                                    <p>
                                        Travelling day in and day out. Doing business like this takes much more effort than doing your own business at home, and on top of that there's the curse of travelling, worries about making train connections, bad and irregular food, contact with different people all the time so that you can never get to know anyone or become friendly with them.
                                    </p>

                                    <p>
                                        The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary.
                                    </p>
                                    <p>
                                        To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words. If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is.
                                    </p>
                                    <p class="small">
                                        <strong>Best regards, Anthony Smith </strong>
                                    </p>

                                    <div class="m-t-lg">
                                        <p>
                                            <span><i class="fa fa-paperclip"></i> 2 attachments - </span>
                                            <a href="#">Download all</a>
                                            |
                                            <a href="#">View all images</a>
                                        </p>

                                        <div class="attachment">
                                            <div class="file-box">
                                                <div class="file">
                                                    <a href="#">
                                                        <span class="corner"></span>

                                                        <div class="icon">
                                                            <i class="fa fa-file"></i>
                                                        </div>
                                                        <div class="file-name">
                                                            Document_2014.doc
                                                            <br>
                                                            <small>Added: Jan 11, 2014</small>
                                                        </div>
                                                    </a>
                                                </div>

                                            </div>
                                            <div class="file-box">
                                                <div class="file">
                                                    <a href="#">
                                                        <span class="corner"></span>

                                                        <div class="icon">
                                                            <i class="fa fa-line-chart"></i>
                                                        </div>
                                                        <div class="file-name">
                                                            Seels_2015.xls
                                                            <br>
                                                            <small>Added: May 13, 2015</small>
                                                        </div>
                                                    </a>
                                                </div>

                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>



            </div>
            </div>
        </div>
		<?php include_once('footer.php'); ?>
        </div>
        </div>
	
	
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>
	 <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();
            $('.footable2').footable();

        });

    </script>
	
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../assets/js/custom.min.js"></script>


<!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});

	$(document).ready(function() {
	$(".oceanIn").keyup(function() {
  	var total = 0.0;
    $.each($(".oceanIn"), function(key, input) {
      if(input.value && !isNaN(input.value)) {
        total += parseFloat(input.value);
      }
    });
    $("#oceanTotal").html("Total: " + total);
  });
});

    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
   <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>

	<!-- Data picker -->
    <script src="../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
   <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();

            $('#dateadded').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });
			
			$('#dateadded1').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

            $('#date_modified').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        });

    </script>
	 <!-- Typehead -->
    <script src="../assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>

	<script>
        $(document).ready(function(){
		<?php 
	$get_PatientDetails = mysqlSelect("pharma_customer_name,pharma_customer_phone","pharma_customer","pharma_id='".$admin_id."'","","","","");
	
	?>
            $('.typeahead_1').typeahead({
                source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['pharma_customer_name']."-".$listPat['pharma_customer_phone']."',"; }?>]
            });

            

        });
    </script>

</body>

</html>
