<div>
    <p><?=__('New booking request.', 'therentalshub-request');?></p>
    <div style="margin-top:30px">
        <table cellpadding="5" style="text-align:left;border-spacing:10px;border:1px #ccc solid">
            <tr>
                <th><?=__('Pick-up date/time', 'therentalshub-request');?></th>
                <td><?=htmlentities($vars->sd, ENT_QUOTES);?> at <?=htmlentities($vars->st, ENT_QUOTES);?></td>
            </tr>
            <tr>
                <th><?=__('Drop-off date/time', 'therentalshub-request');?></th>
                <td><?=htmlentities($vars->ed, ENT_QUOTES);?> at <?=htmlentities($vars->et, ENT_QUOTES);?></td>
            </tr>
            <?php if ($vars->car != 0): ?>
            <tr>
                <th><?=__('Selected car', 'therentalshub-request');?></th>
                <td><?=htmlentities($vars->carname, ENT_QUOTES);?></td>
            </tr>
            <?php endif; ?>
            <?php if ($vars->pick != 0): ?>
            <tr>
                <th><?=__('Pick-up location', 'therentalshub-request');?></th>
                <td><?=htmlentities($vars->plocname, ENT_QUOTES);?></td>
            </tr>
            <?php endif; ?>
            <?php if ($vars->drop != 0): ?>
            <tr>
                <th><?=__('Drop-off location', 'therentalshub-request');?></th>
                <td><?=htmlentities($vars->dlocname, ENT_QUOTES);?></td>
            </tr>
            <?php endif; ?>
            <?php if ($vars->flightname != ''): ?>
            <tr>
                <th><?=__('Flight nr.', 'therentalshub-request');?></th>
                <td><?=htmlentities($vars->flightname, ENT_QUOTES);?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <th><?=__('Customer', 'therentalshub-request');?></th>
                <td><?=htmlentities($vars->fname, ENT_QUOTES);?> <?=htmlentities($vars->lname, ENT_QUOTES);?></td>
            </tr>
            <tr>
                <th><?=__('Email', 'therentalshub-request');?></th>
                <td><?=htmlentities($vars->email, ENT_QUOTES);?></td>
            </tr>
            <tr>
                <th><?=__('Phone', 'therentalshub-request');?></th>
                <td><?=htmlentities($vars->phone, ENT_QUOTES);?></td>
            </tr>
            <tr>
                <th><?=__('Notes', 'therentalshub-request');?></th>
                <td><?=htmlentities($vars->notes, ENT_QUOTES);?></td>
            </tr>
        </table>
    </div>
</div>