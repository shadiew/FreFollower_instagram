<?php

/**
 * @var \Wow\Template\View      $this
 * @var array                   $media
 * @var \App\Models\LogonPerson $logonPerson
 */
$logonPerson = $this->get("logonPerson");
$user        = NULL;
if ($this->has("user")) {
    $user = $this->get("user");
}
?>
<h4>Oto Beğeni Tanımlama</h4>
<?php if (is_null($user)) { ?>
    <div class="alert alert-info">
        The profile you will like should not be private! Since the posts of private profiles cannot be accessed, the likes cannot be sent.
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Remaining Credits:</strong> <?php echo $logonPerson->member->toplamOtoBegeniLimitLeft; ?>
            </div>
            <div>
                <strong>Max like you can send:</strong> <?php echo $logonPerson->member->otoBegeniMaxKredi; ?>
            </div>
        </div>
    </div>
    <div class="card mb-3" style="border-top: 2px solid red;">
        <div class="card-body">
            Until the end date, all the posts of the user will be given the number of likes you set.
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <p>With the Auto Like Definition tool, you can save the number of likes per post you specify to the user you want. The user's profile must be open.</p>
            <form method="post" action="?formType=findUserID" class="form">
                <div class="form-group mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="receh_man" required>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
<?php } else { ?>
    <div class="alert alert-info">
        The profile you will like should not be private! Since the posts of private profiles cannot be accessed, the likes cannot be sent.
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div><strong>Remaining Credits:</strong> <?php echo $logonPerson->member->toplamOtoBegeniLimitLeft; ?>
            </div>
            <div>
                <strong>Max like you can send:</strong> <?php echo $logonPerson->member->otoBegeniMaxKredi; ?>
            </div>
        </div>
    </div>
    <div class="card mb-3" style="border-top: 2px solid red;">
        <div class="card-body">
            Until the end date, all the posts of the user will be given the number of likes you set.
        </div>
    </div>
    <div class="card bg-white shadow-sm">
        <div class="card-body">
            <form id="formOtoBegeni" action="?formType=send" class="form" method="post" onsubmit="return controlBayiForm();">
                <div class="form-group mb-3">
                    <label class="form-label d-block"><?php echo "@" . $user["user"]["username"]; ?></label>
                    <img src="<?php echo "data:image/png;base64, " . base64_encode(file_get_contents(str_replace("http:", "https:", $user["user"]["profile_pic_url"]))); ?>" class="img-thumbnail mb-3" />
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Starting date</label>
                    <input class="form-control" type="text" name="startDate" value="<?php echo date("d.m.Y H:i:s"); ?>" required>
                    <span class="form-text">Enter in the format dd.mm.yyyy hh:mm:ss. The date cannot be less than the current time. If you write a past tense, we will consider it as the present tense.</span>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Time</label>
                    <select class="form-control" name="days" required>
                        <?php for ($i = 1; $i <= 365; $i++) { ?>
                            <option value="<?php echo $i; ?>" <?php echo 365 == $i ? ' selected="selected"' : ''; ?>><?php echo $i . " days"; ?></option>
                        <?php } ?>
                    </select>
                    <span class="form-text">Max <?php echo 365 ?> days.</span>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Number of Likes Per Post:</label>
                    <div class="input-group">
                        <span class="input-group-text">Min</span>
                        <input type="number" name="minadet" class="form-control" placeholder="100" value="100" required>
                        <span class="input-group-text">Max</span>
                        <input type="number" name="maxadet" class="form-control" placeholder="200" value="200" required>
                    </div>
                    <span class="form-text">You can enter min 1, max <?php echo $logonPerson->member->otoBegeniMaxKredi; ?> per post. If you want to specify an integer, you must enter the same number in both fields! The system determines a random number between the min and max numbers you enter for each shared post and sends as many likes as this number. This option has been added because sending the same number of likes to each post creates discomfort for customers!</span>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Frequency of Posting Likes</label>
                    <input class="form-control" type="number" name="minuteDelay" value="1" required>
                    <span class="form-text">How many likes will be sent to the detected posts?</span>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Number of Likes and Posts per Frequency</label>
                    <input class="form-control" type="number" name="krediDelay" value="250" required>
                    <span class="form-text d-block">A few likes will be sent in the x minutes you wrote above.</span>
                    <span class="form-text text-danger">This field cannot be less than 1/20 of the Number of Likes per Post and cannot be larger than 250! If you enter small, it is set to one in 20, if you enter large, it is set to 250!</span>
                </div>
                <?php if ($logonPerson->member->otoBegeniGender === 1) { ?>
                    <div class="form-group mb-3">
                        <label class="form-label">Gender:</label>
                        <select name="gender" class="form-control">
                            <option value="0">Mix</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>
                <?php } ?>
                <input type="hidden" name="userID" value="<?php echo $user["user"]["pk"]; ?>">
                <input type="hidden" name="userName" value="<?php echo $user["user"]["username"]; ?>">
                <input type="hidden" name="imageUrl" value="<?php echo str_replace("http:", "https:", $user["user"]["profile_pic_url"]); ?>">
                <button type="submit" id="formOtoBegeniSubmitButton" class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
<?php } ?>

<?php $this->section("section_scripts");
$this->parent();
if (!is_null($user)) { ?>
    <script type="text/javascript">
        function controlBayiForm() {
            countBegeniMin = parseInt($('#formOtoBegeni input[name=minadet]').val());
            countBegeniMax = parseInt($('#formOtoBegeni input[name=maxadet]').val());
            if (isNaN(countBegeniMin) || countBegeniMin <= 0) {
                alert('Min likes from 1 to <?php echo $logonPerson->member->otoBegeniMaxKredi; ?> must be between!');
                return false;
            }
            if (isNaN(countBegeniMax) || countBegeniMax <= 0) {
                alert('Max number of likes from 1 to <?php echo $logonPerson->member->otoBegeniMaxKredi; ?> must be between!');
                return false;
            }
            if (countBegeniMax > <?php echo $logonPerson->member->otoBegeniMaxKredi; ?>) {
                alert('Number of likes max <?php echo $logonPerson->member->otoBegeniMaxKredi; ?> !');
                return false;
            }
            if (countBegeniMin > countBegeniMax) {
                alert('The minimum number of likes cannot exceed the maximum!');
                return false;
            }
            $('#formOtoBegeniSubmitButton').html('<i class="fa fa-spinner fa-spin"></i> Please wait..');
            $('#formOtoBegeni button').attr('disabled', 'disabled');
        }
    </script>
<?php }
$this->endSection(); ?>