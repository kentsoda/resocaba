<?php
require __DIR__ . '/../inc/layout.php';
require __DIR__ . '/../inc/db.php';
require __DIR__ . '/../inc/csrf.php';
require __DIR__ . '/../inc/form.php';
require __DIR__ . '/../inc/editor.php';
require_once __DIR__ . '/../../../config/functions.php';

renderLayout('求人 編集/新規', function () {
    $pdo = db();
    $id = isset($_GET['id']) ? max(0, (int)$_GET['id']) : 0;
    $isEdit = $id > 0;

    $errors = [];
    $values = [
        'title' => '',
        'status' => 'draft',
        'store_id' => 0,
        'salary' => '',
        'currency' => '',
        'job_type' => '',
        'card_message' => '',
        'description' => '',
        'meta_json' => '',
    ];

    // Options per spec
    $jobTypeOptions = ['', 'キャスト', 'ホールスタッフ', '店長候補', '店長'];
    $salaryUnitOptions = ['HOUR' => '時給', 'DAY' => '日給', 'MONTH' => '月給'];
    $minTermOptions = ['未選択','約1週間','約2週間','約1ヶ月','約2,3ヶ月','約6ヶ月','約1年','1年以上'];
    $homeSectionsOptions = ['pickup' => 'ピックアップ', 'new' => '新着', 'overseas' => '海外', 'domestic' => '国内', 'popular' => '人気', 'long' => '長期', 'short' => '短期'];
    $qualificationsOptions = [
        '18歳以上（高校生不可）','20歳以上','～25歳まで','～30歳まで','～35歳まで','女性のみ','日本国籍限定','国籍不問','有効パスポート所持','就労ビザ取得','経験者のみ','フロア接客経験者のみ','日常英会話必須','飲酒可'
    ];
    $benefitsOptions = [
        '渡航・ビザ・移動' => ['航空チケット往復無料','片道航空券支給','空港送迎あり','現地移動費支給','ビザ取得サポート','就労ビザ費用会社負担','渡航前手続き代行（SIM/両替/保険案内）'],
        '住居・生活サポート' => ['寮完備（無料）','寮完備（格安）','個室寮／マンション寮','Wi-Fi完備','まかないあり（食事提供）','食事手当あり','送迎あり（寮⇄店舗）'],
        '給与' => ['日払いOK','週払いOK','月払い（振込）','最低時給保証あり','指名バックあり','同伴バックあり','入店祝い金あり','昇給あり（随時）','ボーナス／歩合制度あり','皆勤手当・遅刻控除なし'],
        '勤務条件' => ['短期OK（1〜2週間）','中期OK（1〜3か月）','長期歓迎（3か月〜）','シフト自由・自己申告','週1〜OK／1日3h〜OK','友達同士の応募OK'],
        'ルール' => ['ノルマなし','罰金・ペナルティなし','同伴・アフター強制なし','飲酒強制なし（ノンアルOK）','客引き・外販なし','連絡先交換強制なし','ハラスメント対策明記','日本人スタッフ常駐','相談窓口あり'],
        '制服・美容・備品' => ['ドレスレンタルあり','ヒール・小物レンタルあり','ヘアメイク代無料／補助あり','ロッカー完備（個人鍵付き）','メイク・衣装アドバイスあり'],
        '研修・サポート' => ['未経験者歓迎（研修あり）','接客マナー研修','英語／現地語サポートあり','通訳サポートあり','緊急時サポートあり'],
        '海外オプション' => ['ビザ更新費用負担','在留手続き同行','外貨手当','住民登録・税手続き案内','国際送金サポート（給与送金）'],
        'リゾート特有オプション' => ['リゾート施設利用可（ジム・プール等）','シーズン手当（繁忙期）'],
    ];

    // Auxiliary: stores, tags
    $stores = [];
    if ($pdo) {
        try {
            $st = $pdo->query('SELECT id, name FROM stores ORDER BY name ASC');
            $stores = $st ? ($st->fetchAll(PDO::FETCH_ASSOC) ?: []) : [];
        } catch (Throwable $e) {}
    }
    $tags = [];
    $tagGroups = [];
    if ($pdo) {
        try {
            $sql = "SELECT id, name, category FROM tags ORDER BY CASE WHEN category IS NULL OR category = '' THEN 1 ELSE 0 END, category ASC, sort_order ASC, id ASC";
            $st = $pdo->query($sql);
            $tags = $st ? ($st->fetchAll(PDO::FETCH_ASSOC) ?: []) : [];
            foreach ($tags as $tag) {
                $catLabel = isset($tag['category']) ? trim((string)$tag['category']) : '';
                $groupLabel = $catLabel !== '' ? $catLabel : '未分類';
                if (!array_key_exists($groupLabel, $tagGroups)) {
                    $tagGroups[$groupLabel] = [];
                }
                $tagGroups[$groupLabel][] = $tag;
            }
        } catch (Throwable $e) {}
    }

    // Load existing when editing (via shared get_job_by_id)
    if ($isEdit) {
        try {
            if (function_exists('get_job_by_id')) {
                $job = get_job_by_id($id);
                if (is_array($job)) {
                    foreach ($values as $k => $_) {
                        if (array_key_exists($k, $job) && (is_scalar($job[$k]) || $job[$k] === null)) {
                            $values[$k] = (string)($job[$k] ?? '');
                        }
                    }
                    if (isset($job['store_id'])) {
                        $values['store_id'] = (int)$job['store_id'];
                    }
                    if (isset($job['meta_json']) && is_string($job['meta_json'])) {
                        $values['meta_json'] = $job['meta_json'];
                    }
                }
            }
        } catch (Throwable $e) {
            error_log('[admin] jobs/edit load error: ' . $e->getMessage());
        }
    }

    // Decode meta for display
    $meta = [];
    if ($values['meta_json'] !== '') {
        $decoded = json_decode($values['meta_json'], true);
        if (is_array($decoded)) $meta = $decoded;
    }

    // Handle save
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        requireValidCsrfOrAbort();
        $values['title'] = trim(getPostString('title', 255));
        $values['status'] = getPostEnum('status', ['published', 'draft', 'archived'], 'draft');
        $values['store_id'] = getPostInt('store_id', 0);
        $values['salary'] = trim(getPostString('salary', 255));
        $values['currency'] = trim(getPostString('currency', 16));
        $jt = trim(getPostString('job_type', 64));
        $values['job_type'] = in_array($jt, $jobTypeOptions, true) ? $jt : '';
        $values['card_message'] = trim(getPostString('card_message', 255));
        $values['description'] = sanitizeAllowedHtml(getPostString('description'));
        // Build meta from spec fields
        $m = [];
        $m['region_prefecture'] = trim(getPostString('region_prefecture', 255));
        $m['salary_min'] = getPostInt('salary_min', 0);
        $m['salary_max'] = getPostInt('salary_max', 0);
        $m['salary_unit'] = getPostEnum('salary_unit', array_keys($salaryUnitOptions), '');
        $m['message_html'] = sanitizeAllowedHtml(getPostString('message_html'));
        $m['work_content_html'] = sanitizeAllowedHtml(getPostString('work_content_html'));
        $m['job_code'] = trim(getPostString('job_code', 100));
        $mt = trim(getPostString('min_term', 32));
        $m['min_term'] = in_array($mt, $minTermOptions, true) ? $mt : '未選択';
        $m['business_hours'] = trim(getPostString('business_hours', 255));
        $m['regular_holiday'] = trim(getPostString('regular_holiday', 255));
        $m['valid_through'] = getPostDateString('valid_through');
        $m['qualifications'] = getPostArrayStrings('qualifications');
        $benefSel = [];
        foreach ($benefitsOptions as $cat => $opts) {
            $benefSel[$cat] = isset($_POST['benefits_' . md5($cat)]) && is_array($_POST['benefits_' . md5($cat)]) ? (array)$_POST['benefits_' . md5($cat)] : [];
        }
        $m['benefits'] = $benefSel;
        $m['home_sections'] = getPostArrayStrings('home_sections');
        $m['tag_ids'] = getPostArrayInt('tag_ids');
        // Merge any additional meta[*]
        if (isset($_POST['meta']) && is_array($_POST['meta'])) {
            foreach ($_POST['meta'] as $k => $v) {
                if (!array_key_exists($k, $m)) $m[$k] = $v;
            }
        }
        $values['meta_json'] = json_encode($m, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($values['title'] === '') {
            $errors[] = 'タイトルは必須です';
        }
        if (!in_array($values['status'], ['published', 'draft', 'archived'], true)) {
            $errors[] = 'ステータスが不正です';
        }
        if ($values['store_id'] <= 0) {
            $errors[] = '店舗は必須です';
        }

        if (!$errors && $pdo) {
            try {
                $pdo->beginTransaction();
                if ($isEdit) {
                    $sql = 'UPDATE jobs SET title=:title, status=:status, store_id=:store_id, salary=:salary, currency=:currency, job_type=:job_type, card_message=:card_message, description=:description, meta_json=:meta_json, updated_at=NOW() WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                } else {
                    $sql = 'INSERT INTO jobs (title, status, store_id, salary, currency, job_type, card_message, description, meta_json, created_at, updated_at) VALUES (:title,:status,:store_id,:salary,:currency,:job_type,:card_message,:description,:meta_json,NOW(),NOW())';
                    $stmt = $pdo->prepare($sql);
                }
                $stmt->bindValue(':title', $values['title']);
                $stmt->bindValue(':status', $values['status']);
                $stmt->bindValue(':store_id', $values['store_id'], PDO::PARAM_INT);
                $stmt->bindValue(':salary', $values['salary']);
                $stmt->bindValue(':currency', $values['currency']);
                $stmt->bindValue(':job_type', $values['job_type']);
                $stmt->bindValue(':card_message', $values['card_message']);
                $stmt->bindValue(':description', $values['description']);
                $stmt->bindValue(':meta_json', $values['meta_json']);
                $stmt->execute();
                if (!$isEdit) {
                    $id = (int)$pdo->lastInsertId();
                }
                $pdo->commit();
                header('Location: /admin/jobs/edit.php?id=' . $id . '&saved=1');
                exit;
            } catch (Throwable $e) {
                if ($pdo && $pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log('[admin] jobs/edit save error: ' . $e->getMessage());
                $errors[] = '保存に失敗しました'. $e->getMessage();
            }
        }
    }

    ?>
    <h1>求人 編集/新規</h1>
    <?php if ($isEdit): ?>
      <p>ID: <?= (int)$id ?></p>
    <?php endif; ?>
    <?php if (isset($_GET['saved'])): ?>
      <div class="card" style="border-color:#22c55e;">保存しました</div>
    <?php endif; ?>
    <?php if ($errors): ?>
      <div class="card" style="border-color:#ef4444;"><?= htmlspecialchars(implode("\n", $errors), ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <?php csrf_field(); ?>
      <div style="display:grid; gap:12px; max-width:920px;">
        <label>タイトル<br><input type="text" name="title" value="<?= htmlspecialchars($values['title'], ENT_QUOTES, 'UTF-8') ?>" required></label>
        <label>ステータス<br>
          <select name="status">
            <?php foreach (['published' => '公開', 'draft' => '下書き', 'archived' => '非公開'] as $k => $label): ?>
              <option value="<?= htmlspecialchars($k, ENT_QUOTES, 'UTF-8') ?>"<?= $values['status'] === $k ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label>店舗<br>
          <select name="store_id" required>
            <option value="">選択してください</option>
            <?php foreach ($stores as $s): $sel = ((int)$values['store_id'] === (int)$s['id']) ? ' selected' : ''; ?>
              <option value="<?= (int)$s['id'] ?>"<?= $sel ?>><?= htmlspecialchars((string)$s['name'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <div style="display:grid; grid-template-columns: 1fr 160px; gap:12px;">
          <label>給与<br><input type="text" name="salary" value="<?= htmlspecialchars($values['salary'], ENT_QUOTES, 'UTF-8') ?>"></label>
          <label>通貨<br><input type="text" name="currency" value="<?= htmlspecialchars($values['currency'], ENT_QUOTES, 'UTF-8') ?>" placeholder="JPY"></label>
        </div>
        <label>募集職種<br>
          <select name="job_type">
            <?php foreach ($jobTypeOptions as $opt): $lab = $opt === '' ? '未選択' : $opt; ?>
              <option value="<?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?>"<?= ($values['job_type'] === $opt) ? ' selected' : '' ?>><?= htmlspecialchars($lab, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label>カード表示文<br><input type="text" name="card_message" value="<?= htmlspecialchars($values['card_message'], ENT_QUOTES, 'UTF-8') ?>"></label>
        <label>概要メッセージ（HTML）<br><textarea class="js-wysiwyg" name="message_html" rows="10"><?= htmlspecialchars((string)($meta['message_html'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea></label>
        <label>お仕事の内容（HTML）<br><textarea class="js-wysiwyg" name="work_content_html" rows="12"><?= htmlspecialchars((string)($meta['work_content_html'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea></label>

        <fieldset style="border:1px solid #e5e7eb; padding:12px;">
          <legend>勤務地・給与</legend>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <label>地域（都道府県等）<br><input type="text" name="region_prefecture" value="<?= htmlspecialchars((string)($meta['region_prefecture'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
            <label>求人番号<br><input type="text" name="job_code" value="<?= htmlspecialchars((string)($meta['job_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
            <label>給与（最小）<br><input type="number" name="salary_min" value="<?= (int)($meta['salary_min'] ?? 0) ?>" min="0"></label>
            <label>給与（最大）<br><input type="number" name="salary_max" value="<?= (int)($meta['salary_max'] ?? 0) ?>" min="0"></label>
            <label>給与単位<br>
              <select name="salary_unit">
                <?php $suNow = (string)($meta['salary_unit'] ?? ''); foreach ($salaryUnitOptions as $k => $lab): ?>
                  <option value="<?= htmlspecialchars($k, ENT_QUOTES, 'UTF-8') ?>"<?= $suNow === $k ? ' selected' : '' ?>><?= htmlspecialchars($lab, ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
              </select>
            </label>
            <label>最低勤務期間<br>
              <select name="min_term">
                <?php $mtNow = (string)($meta['min_term'] ?? '未選択'); foreach ($minTermOptions as $opt): ?>
                  <option value="<?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?>"<?= $mtNow === $opt ? ' selected' : '' ?>><?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
              </select>
            </label>
          </div>
        </fieldset>

        <fieldset style="border:1px solid #e5e7eb; padding:12px;">
          <legend>営業情報</legend>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <label>営業時間<br><input type="text" name="business_hours" value="<?= htmlspecialchars((string)($meta['business_hours'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
            <label>店休日<br><input type="text" name="regular_holiday" value="<?= htmlspecialchars((string)($meta['regular_holiday'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
            <label>掲載期限<br><input type="date" name="valid_through" value="<?= htmlspecialchars((string)($meta['valid_through'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
          </div>
        </fieldset>

        <fieldset style="border:1px solid #e5e7eb; padding:12px;">
          <legend>応募資格</legend>
          <div style="display:flex; flex-wrap:wrap; gap:12px;">
            <?php $selectedQual = (array)($meta['qualifications'] ?? []); foreach ($qualificationsOptions as $opt): $chk = in_array($opt, $selectedQual, true) ? ' checked' : ''; ?>
              <label style="display:flex; gap:6px; align-items:center;"><input type="checkbox" name="qualifications[]" value="<?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?>"<?= $chk ?>><?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?></label>
            <?php endforeach; ?>
          </div>
        </fieldset>

        <fieldset style="border:1px solid #e5e7eb; padding:12px;">
          <legend>待遇・福利厚生</legend>
          <?php foreach ($benefitsOptions as $cat => $opts): $selArr = (array)(($meta['benefits'][$cat] ?? [])); $name = 'benefits_' . md5($cat) . '[]'; ?>
            <div style="margin-bottom:8px;"><strong><?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?></strong>
              <div style="display:flex; flex-wrap:wrap; gap:12px; margin-top:6px;">
                <?php foreach ($opts as $opt): $chk = in_array($opt, $selArr, true) ? ' checked' : ''; ?>
                  <label style="display:flex; gap:6px; align-items:center;"><input type="checkbox" name="<?= $name ?>" value="<?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?>"<?= $chk ?>><?= htmlspecialchars($opt, ENT_QUOTES, 'UTF-8') ?></label>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </fieldset>

        <fieldset style="border:1px solid #e5e7eb; padding:12px;">
          <legend>トップページ表示カテゴリ</legend>
          <?php $selHome = (array)($meta['home_sections'] ?? []); foreach ($homeSectionsOptions as $key => $lab): $chk = in_array($key, $selHome, true) ? ' checked' : ''; ?>
            <label style="margin-right:12px;"><input type="checkbox" name="home_sections[]" value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"<?= $chk ?>> <?= htmlspecialchars($lab, ENT_QUOTES, 'UTF-8') ?></label>
          <?php endforeach; ?>
        </fieldset>

        <fieldset style="border:1px solid #e5e7eb; padding:12px;">
          <legend>タグ</legend>
          <?php $selTags = (array)($meta['tag_ids'] ?? []); if ($tagGroups): ?>
            <?php foreach ($tagGroups as $groupLabel => $tagList): ?>
              <div style="margin-bottom:8px;">
                <strong><?= htmlspecialchars((string)$groupLabel, ENT_QUOTES, 'UTF-8') ?></strong>
                <div style="display:flex; flex-wrap:wrap; gap:12px; margin-top:6px;">
                  <?php foreach ($tagList as $t): $tid = (int)$t['id']; $chk = in_array($tid, $selTags, true) ? ' checked' : ''; ?>
                    <label><input type="checkbox" name="tag_ids[]" value="<?= $tid ?>"<?= $chk ?>> <?= htmlspecialchars((string)$t['name'], ENT_QUOTES, 'UTF-8') ?></label>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="color:#64748b;">タグは未設定です</p>
          <?php endif; ?>
        </fieldset>

        <label>詳細（WYSIWYG・レガシー）<br><textarea class="js-wysiwyg" name="description" rows="8"><?= htmlspecialchars($values['description'], ENT_QUOTES, 'UTF-8') ?></textarea></label>

        <div style="display:flex; gap:8px;">
          <button type="submit">保存</button>
          <a href="/admin/jobs/" class="button">一覧に戻る</a>
        </div>
      </div>
    </form>
    <?php

    // Enable TinyMCE
    enableWysiwyg('.js-wysiwyg');
});

