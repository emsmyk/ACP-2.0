<? $api_sb_groups = Model('Sourcebans')->groups( SQL::one('SELECT `prefix_sb` FROM `acp_serwery` WHERE `serwer_id` = '.Get::int('serwer_id').' LIMIT 1') ); ?>

<div class="modal fade" id="admin_list_add_admin">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Dodaj Admina</h4>
      </div>
      <div class="modal-body">
        <form method='post'>
          <input type="hidden" name="serwer_id" value="<?= $srv_dane->serwer_id ?>">
          <? $api_sb_last_admin = Model('Sourcebans')->last_admin($srv_dane->prefix_sb); ?>
          <input type="hidden" name="last_admin" value="<?= $api_sb_last_admin ?>">
          <input type="hidden" name="site" value="<?= SITE ?>">
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Nick</span>
              <input class="form-control" name="nick">
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Steam_ID</span>
              <input class="form-control" name="steam">
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Ranga</span>
              <select class="form-control" name="ranga">
                <? foreach ($api_sb_groups as $key): ?>
                <option value="<?= $key->id ?>:<?= $key->name ?>"><?= $key->name ?></option>
                <? endforeach; ?>
              </select>
            </div>
          </p>
          <p>
            <input type="checkbox" name="changelog" checked> Dodaj informację do changelogu serwera
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
          <button type="input" name="admin_list_add_admin_from" class="btn btn-primary">Dodaj</button>
        </form>
      </div>
    </div>
  </div>
</div>

<? foreach ($edit_admin as $value) { ?>
<div class="modal fade" id="admin_list_edit_<?= $value->aid ?>">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edytuj Admina - <?= $value->user ?></h4>
      </div>
      <div class="modal-body">
        <form method='post'>
          <input type="hidden" name="aid" value="<?= $value->aid ?>">
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Nick</span>
              <input class="form-control" name="nick" value="<?= $value->user ?>">
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Steam_ID</span>
              <input class="form-control" name="steam" value="<?= $value->authid ?>">
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Ranga</span>
              <select class="form-control" name="ranga">
                <option value="0:<?= $value->srv_group ?>"><?= $value->srv_group ?></option>
                <? foreach ($api_sb_groups as $key): ?>
                <option value="<?= $key->id ?>:<?= $key->name ?>"><?= $key->name ?></option>
                <? endforeach; ?>
              </select>
            </div>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
          <button type="input" name="admin_list_edytuj" class="btn btn-primary">Edytuj</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="admin_list_usun_<?= $value->aid ?>" >
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-danger">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Usuń admina <?= $value->user ?> (ID: <?= $value->aid ?>)</h4>
      </div>
      <div class="modal-body">
        Czy jesteś pewny że chcesz usunąć admina <?= $value->user ?> (ID: <?= $value->aid ?>) ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Nie</button>
        <form method="post">
          <input type="hidden" name="aid" value="<?= $value->aid ?>">
          <button type="input" class="btn btn-outline pull-right" name="admin_list_usun">Tak</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="admin_list_degradacja_<?= $value->aid ?>" >
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-warning">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Degradacja admina <?= $value->user ?> (ID: <?= $value->aid ?>)</h4>
      </div>
      <div class="modal-body">
        Czy jesteś pewny że chcesz zdegradować admina <?= $value->user ?> (ID: <?= $value->aid ?>) ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Nie</button>
        <form method="post">
          <input type="hidden" name="aid" value="<?= $value->aid ?>">
          <button type="input" class="btn btn-outline pull-right" name="admin_list_degradacja">Tak</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="admin_list_rezygnacja_<?= $value->aid ?>" >
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-info">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Degradacja admina <?= $value->user ?> (ID: <?= $value->aid ?>)</h4>
      </div>
      <div class="modal-body">
        Czy jesteś pewny że chcesz oznaczyć admina <?= $value->user ?> (ID: <?= $value->aid ?>) że zrezgynował ze swojej funkcji?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Nie</button>
        <form method="post">
          <input type="hidden" name="aid" value="<?= $value->aid ?>">
          <button type="input" class="btn btn-outline pull-right" name="admin_list_rezygnacja">Tak</button>
        </form>
      </div>
    </div>
  </div>
</div>
<? } ?>

<div class="modal fade" id="admin_list_ustawienia">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Ustawienia Listy Adminów</h4>
      </div>
      <div class="modal-body">
        <form method='post'>
          <h4>Pokaż/Ukryj Pozycje:</h4>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Opiekun</span>
              <select class="form-control" name="pok_ukr_opiekun">
                <?= Controller('ServerAdminList')->list_adminow_ust($lista_adminow->dane->pokaz_opiekuna); ?>
              </select>
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Zastępca</span>
              <select class="form-control" name="pok_ukr_zastepca">
                <?= Controller('ServerAdminList')->list_adminow_ust($lista_adminow->dane->pokaz_zastepce); ?>
              </select>
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Legenda</span>
              <select class="form-control" name="pok_ukr_legenda">
                <?= Controller('ServerAdminList')->list_adminow_ust($lista_adminow->dane->pokaz_legende); ?>
              </select>
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Weteran</span>
              <select class="form-control" name="pok_ukr_weteran">
                <?= Controller('ServerAdminList')->list_adminow_ust($lista_adminow->dane->pokaz_weteran); ?>
              </select>
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Bez uprawnień</span>
              <select class="form-control" name="pok_ukr_bezuprawnien">
                <?= Controller('ServerAdminList')->list_adminow_ust($lista_adminow->dane->pokaz_bez_uprawnien); ?>
              </select>
            </div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Ilość Wyświetlanych Admiów</span>
              <input class="form-control" type="number" name="ilosc_adminow" value="<?= $lista_adminow->ilosc_adminow ?>">
            </div>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
          <button type="input" name="admin_list_ustawienia_edit" class="btn btn-primary">Edytuj</button>
        </form>
      </div>
    </div>
  </div>
</div>
