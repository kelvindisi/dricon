<div class="row">
    <div class="col-sm-12 col-md-4">
        <div class="card card-title--floating animated slideInLeft">
        <div class="card-body">
                <div class="card-title text-center bg-success"><h5>MY ACCOUNT</h5></div>
                <div class="card-text">
                    <p>
                        <label for="username"><span class="text-muted">Username:</span></label>
                        <span class="float-right"><?= strtolower($user["username"]); ?></span>
                    </p>
                    <p>
                        <label for="fullname"><span class="text-muted">Full Name:</span></label>
                        <span class="float-right"><?= strtoupper($user["first_name"]." ".$user["last_name"]); ?></span>
                    </p>
                    <p>
                        <label for="userlevel"><span class="text-muted">Userlevel:</span></label>
                        <span class="float-right"><?= $user["userlevel"]; ?></span>
                    </p>
                </div>
                <button type="button" class="btn btn-outline-success btn-sm btn-block" data-toggle="modal" data-target="#userProfile">
                    View Profile
                </button>
            </div>
            <div class="card-footer">
                <span class="text-muted">
                    <small>Last Login:
                        <?php
                            if ($user["last_login"] != null)
                                echo $user["last_login"];
                            else
                                echo "Never logged in";
                        ?>
                    </small>
                </span>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    SUMMARY
                </div>
                <div class="card-text">
                    <div class="members">

                    </div>
                    <div class="penalties">

                    </div>
                    <div class="loan">

                    </div>
                </div>
            </div>
            <div class="card-footer">
                <span class="text-muted"><small>&copy; Copyrights of Dricon Sacco</small></span>
            </div>
        </div>
    </div>
</div>