<div class="table-responsive">
    <table class="table table-striped table-hover dt_table">
        <thead>
            <tr>
                <th>ID NO</th>
                <th>NAME</th>
                <th>OCCUPATION</th>
                <th width="20%">MANAGE</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($members as $member): ?>
            <tr>
                <td><?= $member["id_number"] ?></td>
                <td><?= $member["first_name"]." ".$member["last_name"] ?></td>
                <td><?= $member["occupation"] ?></td>
                <td>
                    <a href="<?= site_url('manager/approveMember/'.$member['id']); ?>" class="btn btn-outline-success btn-sm">Approve</a>
                    <a href="<?= site_url('manager/disapproveMember/'.$member['id']); ?>" class="btn btn-sm btn-outline-danger">Disapprove</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>