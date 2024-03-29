<script>
  $(function () {
    "use strict";

    // AREA CHART
    var area = new Morris.Area({
      element: 'iloscGraczy_Morris',
      resize: true,
      data: [
        <?= Controller('ServerDet')->wykres_pobierz_dane('wykres_graczy_morris', 'data', $_SESSION['ServerDet_'. Get::int('serwer_id') ]['wyk-graczy-zakres'], $serwer_id, $_SESSION['ServerDet_'.Get::int('serwer_id')]['srv_det_graczy'] ); ?>
      ],
      xkey: 'y',
      ykeys: ['item1', 'item2'],
      labels: ['Graczy', 'Wolnych Slotów'],
      pointFillColors:['#ffffff'],
      pointStrokeColors: ['black'],
      lineColors:['red', 'gray'],
      hideHover: 'auto'
    });
    // var area = new Morris.Line({
    //   element: 'hlstats_wykres',
    //   resize: true,
    //   data: [
    //     <?= Controller('ServerDet')->wykres_pobierz_dane('wykres_hlstats', 'data', $_SESSION['ServerDet_'. Get::int('serwer_id') ]['wyk-graczy-zakres']], $serwer_id, $_SESSION['ServerDet_'.Get::int('serwer_id')]['srv_det_graczy'] ); ?>
    //   ],
    //   xkey: 'y',
    //   ykeys: ['item1', 'item2', 'item3', 'item4', 'item5', 'item6'],
    //   labels: ['Graczy', 'Nowych Graczy', 'Zabójstw', 'Nowych Zabójstw', 'HS', 'Nowych HS'],
    //   lineColors: ['#3c8dbc', '#000'],
    //   hideHover: 'auto'
    // });

    // LINE CHART
    var line = new Morris.Line({
      element: 'GOSettiRANK',
      resize: true,
      data: [
          <?= Controller('ServerGosetti')->wykres($serwer_id, $_SESSION['ServerDet_'.Get::int('serwer_id')]['srv_det_gosetti_pozycja'] )->gosetti_rank_all; ?>
      ],
      xkey: 'y',
      ykeys: ['item1', 'item2'],
      labels: ['Rank Ogólnie', 'Rank Tura'],
      lineColors: ['#3c8dbc', '#000'],
      hideHover: 'auto'
    });

    //BAR CHART
    var bar = new Morris.Bar({
      element: 'GOSettiPUNKTY',
      resize: true,
      data: [
        <?= Controller('ServerGosetti')->wykres($serwer_id, $_SESSION['ServerDet_'.Get::int('serwer_id')]['srv_det_gosetti_tura'] )->rank_tura; ?>
      ],
      barColors: ['#3498DB', '#34495E','#26B99A', '#DE8244'],
      xkey: 'y',
      ykeys: ['item1', 'item2', 'item3', 'item4'],
      labels: ['Klikniecia', 'Skiny', 'WPL', 'WWW'],
      hideHover: 'auto'
    });
  });
</script>
