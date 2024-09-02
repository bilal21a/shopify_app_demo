<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Polished</title>
    <!-- <link rel="stylesheet" href="http://localhost:3000/css/bootstrap4/dist/css/bootstrap-custom.css?v=datetime"> -->
    {{-- <link rel="stylesheet" href="{{asset('css/app.css')}}"> --}}
    <link rel="stylesheet" href="{{ asset('css/polished.min.css') }}">
    <!-- <link rel="stylesheet" href="polaris-navbar.css"> -->
    <link rel="stylesheet" href="{{ asset('css/iconic/css/open-iconic-bootstrap.min.css') }}">

    <link rel="icon" href="{{ asset('assets/polished-logo-small.png') }}">
    <style>
        .fw_bold {
            font-weight: bold !important;
        }

        .grid-highlight {
            padding-top: 1rem;
            padding-bottom: 1rem;
            background-color: #5c6ac4;
            border: 1px solid #202e78;
            color: #fff;
        }

        hr {
            margin: 6rem 0;
        }

        hr+.display-3,
        hr+.display-2+.display-3 {
            margin-bottom: 2rem;
        }
    </style>
    <script type="text/javascript">
        document.documentElement.className = document.documentElement.className.replace('no-js', 'js') + (document
            .implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure", "1.1") ? ' svg' : ' no-svg');
    </script>
    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '564839313686027');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=564839313686027&ev=PageView&noscript=1" /></noscript>
    <!-- End Facebook Pixel Code -->
    @yield('css')

</head>

<body>
    <nav class="navbar navbar-expand p-0">
        <a class="navbar-brand text-center col-xs-12 col-md-3 col-lg-2 mr-0" href="index.html"> <img
                src="assets/polished-logo-small.png" alt="logo" width="42px"> Polished</a>
        <button class="btn btn-link d-block d-md-none" data-toggle="collapse" data-target="#sidebar-nav" role="button">
            <span class="oi oi-menu"></span>
        </button>

        <input class="border-dark bg-primary-darkest form-control d-none d-md-block w-50 ml-3 mr-2" type="text"
            placeholder="Search" aria-label="Search">
        <div class="dropdown d-none d-md-block">
            <img class="d-none d-lg-inline rounded-circle ml-1" width="32px" src="assets/azamuddin.jpg"
                alt="MA" />
            <button class="btn btn-link btn-link-primary dropdown-toggle" id="navbar-dropdown" data-toggle="dropdown">
                Muhammad Azamuddin
            </button>
            <div class="dropdown-menu dropdown-menu-right" id="navbar-dropdown">
                <a href="#" class="dropdown-item">Profile</a>
                <a href="#" class="dropdown-item">Setting</a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">Sign Out</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid h-100 p-0">
        <div style="min-height: 100%" class="flex-row d-flex align-items-stretch m-0">
            <div class="polished-sidebar bg-light col-12 col-md-3 col-lg-2 p-0 collapse d-md-inline" id="sidebar-nav">
                <ul class="polished-sidebar-menu ml-0 pt-4 p-0 d-md-block">
                    <input class="border-dark form-control d-block d-md-none mb-4" type="text" placeholder="Search"
                        aria-label="Search" />
                    <li><a href="home.html"><span class="oi oi-home"></span> Home</a></li>
                    <li class="{{request()->Url() == route('product')||request()->Url() == route('product_add') ? "active" :""}}">
                        <a class="d-flex" href="{{ route('product') }}">
                            <span>
                                <svg style="width: 20px" viewBox="0 0 20 20" class="Polaris-Icon__Svg"
                                    focusable="false" aria-hidden="true">
                                    <path d="M13 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"></path>
                                    <path fill-rule="evenodd"
                                        d="M11.276 3.5a3.75 3.75 0 0 0-2.701 1.149l-4.254 4.417a2.75 2.75 0 0 0 .036 3.852l2.898 2.898a2.5 2.5 0 0 0 3.502.033l4.747-4.571a3.25 3.25 0 0 0 .996-2.341v-2.187a3.25 3.25 0 0 0-3.25-3.25h-1.974Zm-1.62 2.19a2.25 2.25 0 0 1 1.62-.69h1.974c.966 0 1.75.784 1.75 1.75v2.187c0 .475-.194.93-.536 1.26l-4.747 4.572a1 1 0 0 1-1.401-.014l-2.898-2.898a1.25 1.25 0 0 1-.016-1.75l4.253-4.418Z">
                                    </path>
                                </svg>
                            </span>
                            Products
                        </a>
                    </li>
                    <li class="{{request()->Url() == route('collection_add') ? "active" :""}}">
                        <a class="d-flex" href="#">

                            Collections
                        </a>
                    </li>
                    <li class=""><a class="d-flex" href="charts.html"><svg  style="width: 20px"  viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true"><path fill-rule="evenodd" d="M4.255 5.847a2.75 2.75 0 0 1 2.72-2.347h6.05a2.75 2.75 0 0 1 2.72 2.347l.66 4.46c.063.425.095.853.095 1.282v1.661a3.25 3.25 0 0 1-3.25 3.25h-6.5a3.25 3.25 0 0 1-3.25-3.25v-1.66c0-.43.032-.858.094-1.283l.661-4.46Zm2.72-.847a1.25 1.25 0 0 0-1.236 1.067l-.583 3.933h2.484a1.25 1.25 0 0 1 1.185.855l.159.474a.25.25 0 0 0 .237.171h1.558a.25.25 0 0 0 .237-.17l.159-.475a1.25 1.25 0 0 1 1.185-.855h2.484l-.583-3.933a1.25 1.25 0 0 0-1.236-1.067h-6.05Z"></path></svg>
                         Orders</a></li>
                    <div class="d-block d-md-none">
                        <div class="dropdown-divider"></div>
                        <li><a href="#"> Profile</a></li>
                        <li><a href="#"> Setting</a></li>
                        <li><a href="#"> Sign Out</a></li>
                    </div>
                </ul>
                <div class="pl-3 d-none d-md-block position-fixed" style="bottom: 0px">
                    <span class="oi oi-cog"></span> Setting
                </div>
            </div>
            @yield('content')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
        integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
    <script>
        let ctxArea = document.getElementById('sales')

        var dataArea = {
            labels: ["Jan", "Feb", "March", "April", "May", "June"],
            datasets: [{
                label: 'Sales',
                data: [20, 10, 40, 50, 75, 80],
                backgroundColor: '#6CCC64'
            }, {
                label: 'Add to Cart',
                data: [40, 30, 54, 60, 60, 99],
                backgroundColor: '#FDD638'
            }]
        }

        var myAreaChart = new Chart(ctxArea, {
            type: 'line',
            data: dataArea
        })

        var ctxDoughnut = document.getElementById('top-sales-by-category')
        var myDoughnutChart = new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [10, 20, 30, 32, 54],
                    backgroundColor: ['indigo', 'blue', 'green', 'tan', 'lightgreen']
                }],
                labels: ['Footwear', 'Menswear', 'Bags', 'Sports', 'Gaming']
            }
        })
    </script>
    @yield('js_after')
</body>

</html>
