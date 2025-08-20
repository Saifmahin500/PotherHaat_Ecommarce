<?php
$catStmt = $DB_con->query("SELECT id, category_name FROM categories ORDER BY category_name");

$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<aside class="left-sidebar p-2" style="background:#fff; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1) ;">
    <div class="sticky-sidebar" style="position:sticky; top:20px; ">
        <h5 class="mb-3" style="font-weight:bold; color:#000; border-bottom:2px solid #eee; padding-bottom:8px;">
            <i class="fas fa-filter me-1"  style="color: #B448CF;"></i> SHOP BY CATEGORY
        </h5>
        <div id="catBox" style="display:flex; flex-direction:column; gap:8px;">
            <?php foreach ($categories as $c): ?>
                <label class="cat-item" style="display:flex; align-items:center; gap:8px; cursor:pointer; background:#f8f9fa; padding:6px 10px; border-radius:6px; transition:0.3s;">
                    <input type="checkbox" class="cat-check" value="<?= (int)$c['id'] ?>" name="cat_name" style="accent-color:#B448CF; cursor:pointer;">
                    <span style="font-size:14px; color:#333;"><?= htmlspecialchars($c['category_name']) ?></span>
                </label>
            <?php endforeach; ?>
        </div>

        <button id="clearFilter" class="btn btn-sm mt-3" 
            style="border:1px solid #B448CF; color:#B448CF; background:transparent; transition:0.3s; width:100%;"
            onmouseover="this.style.backgroundColor='#B448CF'; this.style.color='#fff';"
            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#B448CF';">
            <i class="fas fa-times me-1"></i> Clear Filter
        </button>
    </div>
</aside>
