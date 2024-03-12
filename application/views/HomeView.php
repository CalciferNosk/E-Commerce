<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <title>E-commerce</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/owl.theme.green.min.css">
    <style>
        .navbar-dark .nav-item .nav-link {
            color: #fff;
        }

        .navbar-dark .nav-item .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            border-radius: 0.25rem;
            color: #fff;
        }

        .fa-li {
            position: relative;
            left: 0;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <!-- Container wrapper -->
        <div class="container">
            <!-- Navbar brand -->
            <a class="navbar-brand" href="#">
                <img id="shooping" src="https://mdbcdn.b-cdn.net/wp-content/uploads/2018/06/logo-mdb-jquery-small.png" alt="Shopping" draggable="false" height="30" /></a>

            <!-- Toggle button -->
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left links -->
                <ul class="navbar-nav me-3">
                    <li class="nav-item">
                        <a class="nav-link active d-flex align-items-center" aria-current="page" href="#"><i class="fas fa-bars pe-2"></i>Menu</a>
                    </li>
                </ul>
                <!-- Left links -->

                <form class="d-flex align-items-center w-100 form-search">
                    <div class="input-group">
                        <button class="btn btn-light dropdown-toggle shadow-0" type="button" data-mdb-toggle="dropdown" aria-expanded="false" style="padding-bottom: 0.4rem;">
                            All
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark fa-ul">
                            <li>
                                <a class="dropdown-item" href="#"><span class="fa-li pe-2"><i class="fas fa-search"></i></span>All</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"><span class="fa-li pe-2"><i class="fas fa-film"></i></span>Titles</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"><span class="fa-li pe-2"><i class="fas fa-tv"></i></span>TV
                                    Episodes</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"><span class="fa-li pe-2"><i class="fas fa-user-friends"></i></span>Celebs</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"><span class="fa-li pe-2"><i class="fas fa-building"></i></span>Companies</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"><span class="fa-li pe-2"><i class="fas fa-key"></i></span>Keywords</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"><span class="fa-li pe-2"><i class="fas fa-search-plus"></i></span>Advanced
                                    search<i class="fas fa-chevron-right ps-2"></i></a>
                            </li>
                        </ul>
                        <input type="search" class="form-control" placeholder="Search" aria-label="Search" />
                    </div>
                    <a href="#!" class="text-white"><i class="fas fa-search ps-3"></i></a>
                </form>

                <ul class="navbar-nav ms-3">
                    <li class="nav-item me-3">
                        <a class="nav-link d-flex align-items-center" href="#!">Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center me-3" href="#!">
                            <i class="fas fa-bookmark pe-2"></i> Watchlist
                        </a>
                    </li>
                    <li class="nav-item" style="width: 65px;">
                        <a class="nav-link d-flex align-items-center" href="#!">Sign In</a>
                    </li>
                </ul>
            </div>
            <!-- Collapsible wrapper -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->


    <div class="container">


        <div class="footer">
            <div class="owl-carousel owl-theme owl-loaded">
                <div class=" card m-1">
                    content
                </div>
                <div class=" card m-1">
                    content
                </div>
                <div class=" card m-1">
                    content
                </div>
                <div class=" card m-1">
                    content
                </div>
                <div class=" card m-1">
                    content
                </div>
                <div class=" card m-1">
                    content
                </div>
                <div class=" card m-1">
                    content
                </div>

            </div>
        </div>

    </div>
    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.umd.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
    <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/ajaxgoogleapis.min.js"></script>
    <!-- <script src="<?= base_url() ?>assets/js/ajaxgoogleapis.min.js"></script> -->
    <script src="<?= base_url() ?>assets/js/owl.carousel.min.js"></script>
    <script>
        $(document).ready(function() {

            $(".owl-carousel").owlCarousel();
        })
    </script>
</body>

</html>