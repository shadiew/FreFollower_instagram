<?php
    /**
     * @var \Wow\Template\View $this
     * @var array              $model
     */
?>
<h4>Add New Premium Account</h4>
<div class="row mt-4">
    <div class="col-md-6">
        <form method="post" action="?formType=saveBayiEkle">
            <div class="card" style="border-top:5px solid black;">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" value="" class="form-control" placeholder="Input username.." required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Password</label>
                        <input type="text" name="password" value="" class="form-control" placeholder="Input password.." required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">API Balance</label>
                        <input type="text" name="smmbakiye" value="" class="form-control" placeholder="Balance" required autocomplete="off">
                        <span class="form-text">It is the balance of the api service you provide. In the absence of this balance, no transactions can be made from the API.</span>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Likes: Max Points</label>
                                <input type="number" name="begeniMaxKredi" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">1k Likes: Price</label>
                                <input type="text" name="begeniPrice" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Gender</label>
                                <div class="form-check">
                                    <label class="form-label"><input type="checkbox" class="form-check-input" name="begeniGender" value="1">Can Choose</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Reel Likes: Max Points</label>
                                <input type="number" name="begeniMaxKredi" value="<?php echo $bayi["begeniMaxKredi"]; ?>" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">1K Reel Likes: Price</label>
                                <input type="text" name="begeniprice" value="<?php echo $bayi["begeniprice"]; ?>" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Gender</label>
                                <div class="form-check">
                                    <label class="form-label"><input type="checkbox" class="form-check-input" name="begeniGender" value="1"<?php echo $bayi["begeniGender"] == 1 ? ' checked="checked"' : ''; ?>>Can Choose</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Follow: Max Points</label>
                                <input type="number" name="takipMaxKredi" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">1K Follow: Price</label>
                                <input type="text" name="takipPrice" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Gender</label>
                                <div class="form-check">
                                    <label class="form-label"><input type="checkbox" class="form-check-input" name="takipGender" value="1">Can Choose</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Comment: Max Points</label>
                                <input type="number" name="yorumMaxKredi" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">1K Comment: Price</label>
                                <input type="text" name="yorumPrice" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Gender</label>
                                <div class="form-check">
                                    <label class="form-label"><input type="checkbox" class="form-check-input" name="yorumGender" value="1">Can Choose</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Comment Likes: Max Points</label>
                                <input type="number" name="yorumBegeniMaxKredi" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">1K Comment Likes: Price</label>
                                <input type="text" name="yorumBegeniPrice" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Gender</label>
                                <div class="form-check">
                                    <label class="form-label"><input type="checkbox" class="form-check-input" name="yorumBegeniGender" value="1">Can Choose</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Live: Max Points</label>
                                <input type="number" name="canliYayinMaxKredi" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">1K Live: Price</label>
                                <input type="text" name="canliYayinPrice" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Gender</label>
                                <div class="form-check">
                                    <label class="form-label"><input type="checkbox" class="form-check-input" name="canliYayinGender" value="1">Can Choose</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">View Video: Max Points</label>
                                <input type="number" name="videoMaxKredi" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">1K View Video: Price</label>
                                <input type="text" name="videoPrice" value="" class="form-control" required>
                            </div>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Reel View: Max Points</label>
                                <input type="number" name="videoMaxKredi" value="<?php echo $bayi["videoMaxKredi"]; ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">1K Reel View: Price</label>
                                <input type="text" name="videoPrice" value="<?php echo $bayi["videoPrice"]; ?>" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Bookmark: Max Points</label>
                                <input type="number" name="saveMaxKredi" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">1K Bookmark: Price</label>
                                <input type="text" name="savePrice" value="" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Story: Max Points</label>
                                <input type="number" name="storyMaxKredi" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">1K Story: Price</label>
                                <input type="text" name="storyPrice" value="" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Auto Like Package: Max Points</label>
                                <input type="number" name="otoBegeniMaxKredi" value="" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Gender</label>
                                <div class="form-check">
                                    <label class="form-label"><input type="checkbox" class="form-check-input" name="otoBegeniGender" value="1">Can Choose</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Auto Like Package: Max Day</label>
                                <input type="number" name="otoBegeniMaxGun" value="" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check">
                            <label class="form-label"><input type="checkbox" class="form-check-input" value="1" name="isActive"> Active</label>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="col-md-6">
        <div class="card" style="border-top:5px solid green;">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Likes transactions: Limit / Day</label>
                            <input type="number" name="gunlukBegeniLimit" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Likes Daily: Remaining Limit</label>
                            <input type="number" name="gunlukBegeniLimitLeft" value="" class="form-control" required>
                        </div>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Reel Like transactions: Limit / Day</label>
                            <input type="number" name="gunlukBegeniLimit" value="<?php echo $bayi["gunlukBegeniLimit"]; ?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Reel Like Daily: Remaining Limit</label>
                            <input type="number" name="gunlukBegeniLimit" value="<?php echo $bayi["gunlukBegeniLimit"]; ?>" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Follow transactions: Limit / Day</label>
                            <input type="number" name="gunlukTakipLimit" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Follow Daily: Remaining Limit</label>
                            <input type="number" name="gunlukTakipLimitLeft" value="" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Comment transactions: Limit / Day</label>
                            <input type="number" name="gunlukYorumLimit" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Comment Daily: Remaining Limit</label>
                            <input type="number" name="gunlukYorumLimitLeft" value="" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Comment Like transactions: Limit / Day</label>
                            <input type="number" name="gunlukYorumBegeniLimit" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Comment Like Daily: Remaining Limit</label>
                            <input type="number" name="gunlukYorumBegeniLimitLeft" value="" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Live transactions: Limit / Day</label>
                            <input type="number" name="gunlukCanliYayinLimit" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Live Daily: Remaining Limit</label>
                            <input type="number" name="gunlukCanliYayinLimitLeft" value="" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Video transactions: Limit / Day</label>
                            <input type="number" name="gunlukVideoLimit" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Video Daily: Remaining Limit</label>
                            <input type="number" name="gunlukVideoLimitLeft" value="" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Reel Video transactions: Limit / Day</label>
                            <input type="number" name="gunlukVideoLimit" value="<?php echo $bayi["gunlukVideoLimit"]; ?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Reel Video Daily: Remaining Limit</label>
                            <input type="number" name="gunlukVideoLimitLeft" value="<?php echo $bayi["gunlukVideoLimitLeft"]; ?>" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Bookmark transactions: Limit / Day</label>
                            <input type="number" name="gunlukSaveLimit" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Bookmark Video Daily: Remaining Limit</label>
                            <input type="number" name="gunlukSaveLimitLeft" value="" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Story transactions: Limit / Day</label>
                            <input type="number" name="gunlukStoryLimit" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Story Video Daily: Remaining Limit</label>
                            <input type="number" name="gunlukStoryLimitLeft" value="" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Total Auto Likes Limit</label>
                            <input type="number" name="toplamOtoBegeniLimit" value="" class="form-control" required placeholder>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Total Auto Likes: Remaining Limit</label>
                            <input type="number" name="toplamOtoBegeniLimitLeft" value="" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Expire Date</label>
                    <input type="text" name="sonaErmeTarihi" value="" class="form-control" required placeholder="Contoh: <?php echo date("d.m.Y"); ?> 00:00:00">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Catatan</label>
                    <textarea style="resize:none" class="form-control" name="notlar" rows="3"></textarea>
                </div>
                
                <div class="">
                    <button type="submit" class="btn btn-primary">Save Setting</button>
                </div>
            </div>
        </div>
    </div>
    <br/>
    
    </form>
</div>