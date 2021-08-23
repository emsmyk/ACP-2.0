<?
tytul_strony("Galeria Map");
?>
<style>
.content-wrapper { background: url('<?= $acp_system ['tlo_galeria_map']?>') no-repeat center center fixed; -webkit-background-size: cover; -moz-background-size: cover; background-size: cover; -o-background-size: cover; }
p { color: #fff; }
@media (min-width: 1200px){.container { width: 1400px; }}
</style>
<?
$logo = (empty($acp_system['logo_galeria_map'])) ? $acp_system['logo_prawa'] : $acp_system['logo_galeria_map'];
?>
<body class="hold-transition <?= $player->szablon ?> layout-top-nav">
  <?
  if(!empty($_SESSION['user'])){
    require_once("./templates/master/przybornik/menu-header.php");
  }
  ?>
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="container">
        <section class="content">
         <div class="row">
           <section class="col-lg-12">
             <p><?= $Messe->show(); ?></p>
           </section >
         </div>
         <div class="row text-center text-lg-left">
           <a href="<?= $acp_system['strona_www'] ?>"><img src="<?= $logo ?>"></a>
         </div>
         <? echo Model('PublicData')->menu($x, $acp_system['acp_strona_www'], $acp_system['acp_nazwa']); ?>
    		 <div class="row">
          <div class="col-lg-12">
           <?
           if($xx):
             Model('Server')->exist($xx, '?x=pub_galeria_map');
           ?>
           <div class="row text-center text-lg-left">
             <div class="mdb-lightbox no-margin">
               <div id="mdb-lightbox-ui"></div>
               <div class="mdb-lightbox no-margin">
                 <?
                 $dane = Controller('GaleriaMap')->indexPublic($xx);
                 for ($i = 0; $i < count($dane->mapa_img); $i++): ?>
                   <figure class="col-md-6">
                     <a href="<?= $dane->mapa_img[$i] ?>" data-size="1600x1067">
                       <img alt="picture" src="<?= Controller('Imgur')->img($dane->mapa_img[$i], 5) ?>" class="img-fluid" width="auto" height="350px">
                       <div class="carousel-caption">
                         <h1><?= $dane->mapa_nazwa[$i] ?></h1>
                       </div>
                     </a>
                   </figure>
                 <? endfor;?>
               </div>
             </div>
           </div>
           <?  else: ?>
           <div class="box box">
             <div class="box-body">
               <table id="example-top" class="table table-bordered table-striped" width="100%">
                 <thead>
                   <tr>
                     <th></th>
                     <th>Serwer</th>
                     <th>Mod</th>
                     <th></th>
                   </tr>
                 </thead>
                 <tbody>
                   <?
                   $dane = Model('PublicData')->serwer_list($xx);
                   foreach ($dane as $row):
                   ?>
                   <tr>
                     <td><?= $row->istotnosc ?></td>
                     <td><a href="<?= "?x=$x&xx=$row->serwer_id" ?>"><?= $row->nazwa ?></a></td>
                     <td><?= $row->mod ?></td>
                     <td>
                       <a href="<?= "?x=$x&xx=$row->serwer_id" ?>"><button type="button" class="btn btn-primary"><i class="fa fa-ellipsis-h"></i></button></a>
                     </td>
                   </tr>
                   <? endforeach; ?>
                 </tbody>
               </table>
             </div>
           </div>
           <? endif; ?>
          </div>
         </div>

         <? echo Model('PublicData')->social(); ?>
         <? echo Model('PublicData')->stopka(); ?>

        </section>
      </div>
    </div>
  </div>
</body>
<!-- jQuery 3 -->
<script src="./www/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="./www/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="./www/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="./www/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="./www/bower_components/datatables.net-bs/js/dataTables.responsive.js"></script>
<?= Model('DataTable')->table([
  ['name' => '#example-top']
]); ?>
