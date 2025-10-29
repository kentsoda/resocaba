<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/csrf.php';
require __DIR__ . '/../inc/form.php';
require __DIR__ . '/../inc/editor.php';
require_once __DIR__ . '/../../../config/functions.php';

renderLayout('店舗 編集/新規', function () {
    $pdo = db();
    $id = isset($_GET['id']) ? max(0, (int)$_GET['id']) : 0;
    $isEdit = $id > 0;

    $categoryOptions = [
        '' => '選択してください',
        'キャバクラ' => 'キャバクラ',
        'ラウンジ' => 'ラウンジ',
        'クラブ' => 'クラブ',
        'スナック' => 'スナック',
    ];
    $holidayOptions = ['月', '火', '水', '木', '金', '土', '日', '祝', '不定休', '無し'];
    $startHourOptions = ['' => '指定なし'];
    for ($h = 0; $h < 24; $h++) {
        $startHourOptions[(string)$h] = sprintf('%02d:00', $h);
    }
    $endHourOptions = ['' => '指定なし', 'LAST' => 'LAST'];
    for ($h = 0; $h < 24; $h++) {
        $label = sprintf('%02d:00', $h);
        $endHourOptions[$label] = $label;
    }

    $values = [
        'name' => '',
        'slug' => '',
        'category' => '',
        'logo_url' => '',
        'country' => '',
        'region_prefecture' => '',
        'address' => '',
        'phone_domestic' => '',
        'phone_international' => '',
        'business_hours_start' => null,
        'business_hours_end' => '',
        'holiday_list' => [],
        'site_url' => '',
        'description_html' => '',
    ];

    if ($isEdit) {
        if (!$pdo) {
            echo '<p>データベースに接続できません。</p>';
            return;
        }
        try {
            $stmt = $pdo->prepare('SELECT * FROM stores WHERE id = :id LIMIT 1');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $store = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $store = false;
            error_log('[admin] stores/edit load error: ' . $e->getMessage());
        }
        if (!$store) {
            echo '<p>指定された店舗が見つかりません。</p>';
            return;
        }
        foreach ($values as $key => $_) {
            if (array_key_exists($key, $store)) {
                $values[$key] = is_scalar($store[$key]) ? (string)$store[$key] : $store[$key];
            }
        }
        $values['business_hours_start'] = isset($store['business_hours_start']) && $store['business_hours_start'] !== null
            ? (int)$store['business_hours_start']
            : null;
        $values['business_hours_end'] = isset($store['business_hours_end']) && is_string($store['business_hours_end'])
            ? $store['business_hours_end']
            : (string)($store['business_hours_end'] ?? '');
        $values['holiday_list'] = [];
        if (!empty($store['holiday'])) {
            $values['holiday_list'] = array_values(array_filter(array_map('trim', explode(',', (string)$store['holiday']))));
        }
        $values['description_html'] = isset($store['description_html']) ? (string)$store['description_html'] : '';
    }

    $errors = [];
    $saved = isset($_GET['saved']);

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        requireValidCsrfOrAbort();

        $values['name'] = trim(getPostString('name', 255));
        $values['slug'] = trim(getPostString('slug', 191));
        $values['category'] = getPostEnum('category', array_keys($categoryOptions), '');
        $values['logo_url'] = trim(getPostString('logo_url', 2048));
        $values['country'] = trim(getPostString('country', 64));
        $values['region_prefecture'] = trim(getPostString('region_prefecture', 64));
        $values['address'] = trim(getPostString('address', 255));
        $values['phone_domestic'] = trim(getPostString('phone_domestic', 32));
        $values['phone_international'] = trim(getPostString('phone_international', 32));
        $values['site_url'] = trim(getPostString('site_url', 255));
        $values['description_html'] = sanitizeAllowedHtml(getPostString('description_html'));

        $startRaw = isset($_POST['business_hours_start']) ? (string)$_POST['business_hours_start'] : '';
        if ($startRaw === '' || $startRaw === null) {
            $values['business_hours_start'] = null;
        } elseif (preg_match('/^\d{1,2}$/', $startRaw) && (int)$startRaw >= 0 && (int)$startRaw <= 23) {
            $values['business_hours_start'] = (int)$startRaw;
        } else {
            $errors[] = '営業時間（開始）の値が不正です。';
        }

        $values['business_hours_end'] = getPostEnum('business_hours_end', array_keys($endHourOptions), '');

        $holidaySelected = getPostArrayStrings('holiday');
        $holidaySelected = array_values(array_intersect($holidayOptions, $holidaySelected));
        $values['holiday_list'] = $holidaySelected;

        if ($values['name'] === '') {
            $errors[] = '店舗名を入力してください。';
        }
        if ($values['slug'] !== '' && !preg_match('/^[a-z0-9-]+$/', $values['slug'])) {
            $errors[] = 'スラッグは半角英数字とハイフンのみ使用できます。';
        }
        if ($values['logo_url'] !== '' && !filter_var($values['logo_url'], FILTER_VALIDATE_URL)) {
            $errors[] = 'ロゴURLの形式が正しくありません。';
        }
        if ($values['site_url'] !== '' && !filter_var($values['site_url'], FILTER_VALIDATE_URL)) {
            $errors[] = 'サイトURLの形式が正しくありません。';
        }

        if (empty($errors) && $pdo) {
            $holidayString = $values['holiday_list'] ? implode(',', $values['holiday_list']) : null;
            $businessHoursLabel = '';
            if (function_exists('build_store_hours_label')) {
                $businessHoursLabel = build_store_hours_label($values['business_hours_start'], $values['business_hours_end']);
            } else {
                $startLabel = $values['business_hours_start'] !== null ? sprintf('%02d:00', $values['business_hours_start']) : '';
                $endLabel = $values['business_hours_end'];
                if ($startLabel !== '' && $endLabel !== '') {
                    $businessHoursLabel = $startLabel . '〜' . $endLabel;
                } else {
                    $businessHoursLabel = $startLabel ?: $endLabel;
                }
            }
            $businessHoursValue = $businessHoursLabel !== '' ? $businessHoursLabel : null;

            try {
                if ($isEdit) {
                    $sql = 'UPDATE stores SET
                                name = :name,
                                slug = :slug,
                                category = :category,
                                logo_url = :logo_url,
                                description_html = :description_html,
                                country = :country,
                                region_prefecture = :region_prefecture,
                                address = :address,
                                phone_domestic = :phone_domestic,
                                phone_international = :phone_international,
                                business_hours = :business_hours,
                                business_hours_start = :business_hours_start,
                                business_hours_end = :business_hours_end,
                                holiday = :holiday,
                                site_url = :site_url
                              WHERE id = :id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                } else {
                    $sql = 'INSERT INTO stores
                                (name, slug, category, logo_url, description_html, country, region_prefecture, address, phone_domestic, phone_international, business_hours, business_hours_start, business_hours_end, holiday, site_url)
                            VALUES
                                (:name, :slug, :category, :logo_url, :description_html, :country, :region_prefecture, :address, :phone_domestic, :phone_international, :business_hours, :business_hours_start, :business_hours_end, :holiday, :site_url)';
                    $stmt = $pdo->prepare($sql);
                }

                $stmt->bindValue(':name', $values['name']);
                $stmt->bindValue(':slug', $values['slug'] !== '' ? $values['slug'] : null, $values['slug'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
                $stmt->bindValue(':category', $values['category'] !== '' ? $values['category'] : null, $values['category'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
                $stmt->bindValue(':logo_url', $values['logo_url'] !== '' ? $values['logo_url'] : null, $values['logo_url'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
                $stmt->bindValue(':description_html', $values['description_html'] !== '' ? $values['description_html'] : null, $values['description_html'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
                $stmt->bindValue(':country', $values['country'] !== '' ? $values['country'] : null, $values['country'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
                $stmt->bindValue(':region_prefecture', $values['region_prefecture'] !== '' ? $values['region_prefecture'] : null, $values['region_prefecture'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
                $stmt->bindValue(':address', $values['address'] !== '' ? $values['address'] : null, $values['address'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
                $stmt->bindValue(':phone_domestic', $values['phone_domestic'] !== '' ? $values['phone_domestic'] : null, $values['phone_domestic'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
                $stmt->bindValue(':phone_international', $values['phone_international'] !== '' ? $values['phone_international'] : null, $values['phone_international'] !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
                if ($businessHoursValue !== null) {
                    $stmt->bindValue(':business_hours', $businessHoursValue, PDO::PARAM_STR);
                } else {
                    $stmt->bindValue(':business_hours', null, PDO::PARAM_NULL);
                }
                if ($values['business_hours_start'] !== null) {
                    $stmt->bindValue(':business_hours_start', $values['business_hours_start'], PDO::PARAM_INT);
                } else {
                    $stmt->bindValue(':business_hours_start', null, PDO::PARAM_NULL);
                }
                if ($values['business_hours_end'] !== '') {
                    $stmt->bindValue(':business_hours_end', $values['business_hours_end'], PDO::PARAM_STR);
                } else {
                    $stmt->bindValue(':business_hours_end', null, PDO::PARAM_NULL);
                }
                if ($holidayString !== null) {
                    $stmt->bindValue(':holiday', $holidayString, PDO::PARAM_STR);
                } else {
                    $stmt->bindValue(':holiday', null, PDO::PARAM_NULL);
                }
                if ($values['site_url'] !== '') {
                    $stmt->bindValue(':site_url', $values['site_url'], PDO::PARAM_STR);
                } else {
                    $stmt->bindValue(':site_url', null, PDO::PARAM_NULL);
                }

                $stmt->execute();

                if (!$isEdit) {
                    $id = (int)$pdo->lastInsertId();
                    $isEdit = true;
                }

                header('Location: /admin/stores/edit.php?id=' . $id . '&saved=1');
                exit;
            } catch (Throwable $e) {
                error_log('[admin] stores/edit save error: ' . $e->getMessage());
                $errors[] = '保存に失敗しました。';
            }
        }
    }

    ?>
    <h1>店舗 編集/新規</h1>
    <?php if ($isEdit): ?>
      <p>ID: <?= (int)$id ?></p>
    <?php endif; ?>
    <?php if ($saved): ?>
      <div class="card" style="border-color:#22c55e;">保存しました。</div>
    <?php endif; ?>
    <?php if ($errors): ?>
      <div class="card" style="border-color:#ef4444;">
        <?= htmlspecialchars(implode("\n", $errors), ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <?php if ($isEdit): ?>
      <p><a href="/admin/stores/images.php?store_id=<?= (int)$id ?>">画像の並び替え・削除はこちら</a></p>
    <?php endif; ?>

    <form method="post" action="" style="max-width:960px; display:grid; gap:16px;">
      <?php csrf_field(); ?>
      <label>店舗名（必須）<br>
        <input type="text" name="name" value="<?= htmlspecialchars($values['name'], ENT_QUOTES, 'UTF-8') ?>" required>
      </label>
      <label>スラッグ<br>
        <input type="text" name="slug" value="<?= htmlspecialchars($values['slug'], ENT_QUOTES, 'UTF-8') ?>" placeholder="半角英数字・ハイフン">
      </label>
      <label>カテゴリ<br>
        <select name="category">
          <?php foreach ($categoryOptions as $key => $label): ?>
            <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $values['category'] === $key ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>ロゴURL<br>
        <input type="url" name="logo_url" value="<?= htmlspecialchars($values['logo_url'], ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:12px;">
        <label>国<br>
          <input type="text" name="country" value="<?= htmlspecialchars($values['country'], ENT_QUOTES, 'UTF-8') ?>">
        </label>
        <label>地域・都道府県<br>
          <input type="text" name="region_prefecture" value="<?= htmlspecialchars($values['region_prefecture'], ENT_QUOTES, 'UTF-8') ?>">
        </label>
      </div>
      <label>住所<br>
        <input type="text" name="address" value="<?= htmlspecialchars($values['address'], ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:12px;">
        <label>電話番号（国内）<br>
          <input type="text" name="phone_domestic" value="<?= htmlspecialchars($values['phone_domestic'], ENT_QUOTES, 'UTF-8') ?>">
        </label>
        <label>電話番号（海外）<br>
          <input type="text" name="phone_international" value="<?= htmlspecialchars($values['phone_international'], ENT_QUOTES, 'UTF-8') ?>">
        </label>
      </div>
      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:12px;">
        <label>営業時間（開始）<br>
          <select name="business_hours_start">
            <?php foreach ($startHourOptions as $key => $label): ?>
              <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= ($values['business_hours_start'] !== null ? (string)$values['business_hours_start'] : '') === (string)$key ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label>営業時間（終了）<br>
          <select name="business_hours_end">
            <?php foreach ($endHourOptions as $key => $label): ?>
              <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $values['business_hours_end'] === $key ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </label>
      </div>
      <label>店休日<br>
        <select name="holiday[]" multiple size="<?= count($holidayOptions) ?>" style="min-height:140px;">
          <?php foreach ($holidayOptions as $opt): ?>
            <option value="<?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?>"<?= in_array($opt, $values['holiday_list'], true) ? ' selected' : '' ?>><?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>サイトURL<br>
        <input type="url" name="site_url" value="<?= htmlspecialchars($values['site_url'], ENT_QUOTES, 'UTF-8') ?>">
      </label>
      <label>説明（HTML）<br>
        <textarea class="js-wysiwyg" name="description_html" rows="12"><?= htmlspecialchars($values['description_html'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </label>
      <div>
        <button type="submit" class="button">保存する</button>
      </div>
    </form>
    <?php
    enableWysiwyg();
});
