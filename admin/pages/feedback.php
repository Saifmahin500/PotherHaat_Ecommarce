<?php

require_once __DIR__ . '/../../config/dbconfig.php';

$database = new Database();
$conn = $database->dbConnection();

$sql = "SELECT cm.*, 
		CASE WHEN u.id IS NULL THEN 0 ELSE 1 END AS is_registered
		FROM contact_message cm
		LEFT JOIN users u ON u.email = cm.email
		ORDER BY cm.created_at DESC";

$stmt = $conn->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">user Feedback</h4>
    <span class="badge badge-info"> Total: <?= count($rows) ?></span>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name / Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Received</th>
                <th>Status</th>
                <th>Reply</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $i => $r): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td>
                        <div><strong><?= htmlspecialchars($r['name']) ?></strong><?php if ((int)$r['is_registered'] === 0): ?>
                                <small class="text-muted">(Not Registered)</small>
                            <?php endif; ?>
                        </div>
                        <div class="text-muted small"><?= htmlspecialchars($r['email']) ?></div>
                    </td>
                    <td><?= htmlspecialchars($r['subject'] ?: '--') ?></td>

                    <td style="max-width: 360px; white-space: pre-wrap;"><?= nl2br(htmlspecialchars($r['message'])) ?></td>
                    <td class="small"><?= htmlspecialchars($r['created_at']) ?></td>
                    <td>
                        <?php if ((int)$r['is_replied'] === 1): ?>
                            <span class="badge badge-success">Replies</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php endif; ?>
                    </td>

                    <td style="min-width: 240px;">
                        <?php if ((int)$r['is_replied'] === 0): ?>
                            <button class="btn btn-sm btn-primary relpyBtn" data-id="<?= $r['id'] ?>">Reply</button>
                            <div class="replyBox mt-2 d-none" id="rb-<?= $r['id'] ?>">
                                <textarea name="" class="form-control mb-2" rows="3" placeholder="Type your reply..." id="rt-<?= $r['id'] ?>"></textarea>
                                <button class="btn btn-sm btn-success sendReply" data-id="<?= $r['id'] ?>">Send</button>
                                <div class="small text-muted mt-1">This will be emailed to the visitor.</div>
                            </div>
                        <?php else: ?>
                            <div class="small text-muted">
                                Sent at: <?= htmlspecialchars($r['replied_at'] ?: '--') ?>
                            </div>
                            <?php
                            if (!empty($r['reply_text'])): ?>
                                <details class="mt-1">
                                    <summary>View Reply</summary>
                                    <div class="small" style="white-space: pre-wrap;"><?= nl2br(htmlspecialchars($r['reply_text'])) ?></div>
                                </details>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function() {

        $(document).on('click', '.relpyBtn', function() {

            var id = $(this).data('id');
            $('#rb-' + id).toggleClass('d-none');
        });

        $(document).on('click', '.sendReply', function() {
            var id = $(this).data('id');
            var txt = ($('#rt-' + id).val() || '').trim();

            if (!txt) {
                alert("Please type your reply");
                return;
            }

            $.ajax({

                url: 'ajax/send_reply.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    reply: txt
                }
            }).done(function(d) {

                if (d && d.ok) {
                    alert('Reply Sent1');
                    location.reload();
                } else {
                    alert((d && d.err) ? d.err : 'Failed to sent reply');
                }
            }).fail(function(xhr) {
                alert(xhr.responseText || 'Unexpected error');
                console.error('send reply failed', xhr.status, xhr.responseText);
            });
        });
    });
</script>