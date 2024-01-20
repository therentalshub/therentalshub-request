<div>
    <p>Dear <?=htmlentities($vars->fname, ENT_QUOTES);?>,<br>thank you for your booking request.</p>
    <p>Following are your request details.</p>
    <div style="margin-top:30px">
        <table cellpadding="5" style="text-align:left;border-spacing:10px;border:1px #ccc solid">
            <tr>
                <th>Pick-up date/time</th>
                <td><?=htmlentities($vars->sd, ENT_QUOTES);?> at <?=htmlentities($vars->st, ENT_QUOTES);?></td>
            </tr>
            <tr>
                <th>Drop-off date/time</th>
                <td><?=htmlentities($vars->ed, ENT_QUOTES);?> at <?=htmlentities($vars->et, ENT_QUOTES);?></td>
            </tr>
            <?php if ($vars->car != 0): ?>
            <tr>
                <th>Selected car</th>
                <td><?=htmlentities($vars->carname, ENT_QUOTES);?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <th>Customer</th>
                <td><?=htmlentities($vars->fname, ENT_QUOTES);?> <?=htmlentities($vars->lname, ENT_QUOTES);?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?=htmlentities($vars->email, ENT_QUOTES);?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?=htmlentities($vars->phone, ENT_QUOTES);?></td>
            </tr>
            <tr>
                <th>Notes</th>
                <td><?=htmlentities($vars->notes, ENT_QUOTES);?></td>
            </tr>
        </table>
    </div>
</div>