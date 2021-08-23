<?php
class DataTableModel
{
   public function __construct()
   {

   }
   /*
   tables = array [
     name,
     sort,
     sort_type,
     display,
     responsive
   ]
   */
   public function table($tables)
   {
    $tab = '<script> $(function () {   $.extend( true, $.fn.dataTable.defaults, { "language": { "processing":     "Przetwarzanie...", "search":         "Szukaj:", "lengthMenu":     "Pokaż _MENU_ pozycji", "info":           "Pozycje od _START_ do _END_ z _TOTAL_ łącznie", "infoEmpty":      "Pozycji 0 z 0 dostępnych", "infoFiltered":   "(filtrowanie spośród _MAX_ dostępnych pozycji)", "infoPostFix":    "", "loadingRecords": "Wczytywanie...", "zeroRecords":    "Nie znaleziono pasujących pozycji", "emptyTable":     "Brak danych", "paginate": { 	"first":      "Pierwsza", 	"previous":   "Poprzednia", 	"next":       "Następna", 	"last":       "Ostatnia" }, "aria": { 	"sortAscending": ": aktywuj, by posortować kolumnę rosnąco", 	"sortDescending": ": aktywuj, by posortować kolumnę malejąco" } } } );';
    foreach ($tables as $table) {
      $table['name'] = (empty($table['name'])) ? '#example' : $table['name'];
      $table['sort'] = (empty($table['sort'])) ? '0' : $table['sort'];
      $table['sort_type'] = (empty($table['sort_type'])) ? 'asc' : $table['sort_type'];
      $table['display'] = (empty($table['display'])) ? '10' : $table['display'];
      $table['responsive'] = (empty($table['responsive'])) ? 'true' : $table['responsive'];

      $tab.= '$(\''.$table['name'].'\').DataTable({ responsive: '.$table['responsive'].', "iDisplayLength": '.$table['display'].',  "order": [[ '.$table['sort'].', "'.$table['sort_type'].'" ]] });';
    }
    $tab.= '}) </script>';

    return $tab;
   }

}
?>
