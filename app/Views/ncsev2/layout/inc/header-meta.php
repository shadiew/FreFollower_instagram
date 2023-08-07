<title><?php if ($this->has('title')) {
          echo $this->get('title') . " - ";
        }
        echo Wow::get("ayar/site_title"); ?></title>

<?php if ($this->has('description')) { ?>
  <meta name="description" content="<?php echo $this->get('description'); ?>"><?php } ?>

<?php if ($this->has('keywords')) { ?>
  <meta name="keywords" content="<?php echo $this->get('keywords'); ?>"><?php } ?>

<link rel="canonical" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
<!-- Open Graph / Facebook -->
<meta property="og:locale" content="id_ID" />
<meta property="og:type" content="website" />
<meta property="og:title" content="<?php if ($this->has('title')) {
                                      echo $this->get('title') . " - ";
                                    }
                                    echo Wow::get("ayar/site_title"); ?>" />
<meta property="og:description" content="<?php echo $this->get('description'); ?>" />
<meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
<meta property="og:site_name" content="Instagram Informatikamu">
<!-- End Open Graph / Facebook -->

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
<meta property="twitter:title" content="<?php if ($this->has('title')) {
                                          echo $this->get('title') . " - ";
                                        }
                                        echo Wow::get("ayar/site_title"); ?>">
<meta property="twitter:description" content="<?php echo $this->get('description'); ?>">