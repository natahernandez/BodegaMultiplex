@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col-sm mb-2 mb-sm-0">
      <h1 class="page-header-title">Dashboard Principal</h1>
      <p class="page-header-text">Panel de control y estadísticas generales de BodegaMultiplex</p>
    </div>
    
    <div class="col-sm-auto">
      <div class="d-grid d-sm-flex gap-2">
        <!-- Daterangepicker -->
        <button id="js-daterangepicker-predefined" class="btn btn-primary btn-sm dropdown-toggle">
          <i class="bi-calendar-week me-1"></i>
          <span class="js-daterangepicker-predefined-preview"></span>
        </button>
        
        <div class="dropdown">
          <button type="button" class="btn btn-white dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi-gear me-1"></i> Configuración
          </button>
          <div class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item" href="#">
              <i class="bi-graph-up me-2"></i> Configurar reportes
            </a>
            <a class="dropdown-item" href="#">
              <i class="bi-bell me-2"></i> Notificaciones
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">
              <i class="bi-download me-2"></i> Exportar datos
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Page Header -->

<!-- Stats Cards -->
<div class="row mb-4">
  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Ventas Hoy</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="js-counter display-4 text-primary">$7,431.14</span>
          </div>
          <div class="col-auto">
            <i class="bi-graph-up text-success" style="font-size: 2rem;"></i>
          </div>
        </div>
        <span class="badge bg-soft-success text-success">
          <i class="bi-arrow-up"></i> 25.3%
        </span>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Productos Vendidos</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="js-counter display-4 text-info">342</span>
          </div>
          <div class="col-auto">
            <i class="bi-box-seam text-info" style="font-size: 2rem;"></i>
          </div>
        </div>
        <span class="badge bg-soft-info text-info">
          <i class="bi-arrow-up"></i> 12.5%
        </span>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Clientes Atendidos</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="js-counter display-4 text-warning">89</span>
          </div>
          <div class="col-auto">
            <i class="bi-people text-warning" style="font-size: 2rem;"></i>
          </div>
        </div>
        <span class="badge bg-soft-warning text-warning">
          <i class="bi-arrow-up"></i> 8.2%
        </span>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Ganancia Neta</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="js-counter display-4 text-success">$2,150</span>
          </div>
          <div class="col-auto">
            <i class="bi-currency-dollar text-success" style="font-size: 2rem;"></i>
          </div>
        </div>
        <span class="badge bg-soft-success text-success">
          <i class="bi-arrow-up"></i> 15.7%
        </span>
      </div>
    </div>
  </div>
</div>
<!-- End Stats Cards -->

<!-- Card -->
<div class="card mb-3 mb-lg-5">
  <!-- Header -->
  <div class="card-header card-header-content-sm-between">
    <h4 class="card-header-title mb-2 mb-sm-0">Recent projects</h4>

    <!-- Nav -->
    <ul class="nav nav-segment nav-fill" id="projectsTab" role="tablist">
      <li class="nav-item" data-bs-toggle="chart" data-datasets="0" data-trigger="click" data-action="toggle">
        <a class="nav-link active" href="javascript:;" data-bs-toggle="tab">This week</a>
      </li>
      <li class="nav-item" data-bs-toggle="chart" data-datasets="1" data-trigger="click" data-action="toggle">
        <a class="nav-link" href="javascript:;" data-bs-toggle="tab">Last week</a>
      </li>
    </ul>
    <!-- End Nav -->
  </div>
  <!-- End Header -->

  <!-- Body -->
  <div class="card-body">
    <div class="row align-items-sm-center mb-4">
      <div class="col-sm mb-3 mb-sm-0">
        <div class="d-flex align-items-center">
          <span class="h1 mb-0">$7,431.14 USD</span>
          <span class="text-success ms-2">
            <i class="bi-graph-up"></i> 25.3%
          </span>
        </div>
      </div>
      <!-- End Col -->

      <div class="col-sm-auto">
        <!-- Legend Indicators -->
        <div class="row fs-6">
          <div class="col-auto">
            <span class="legend-indicator bg-primary"></span> Income
          </div>
          <div class="col-auto">
            <span class="legend-indicator bg-info"></span> Expenses
          </div>
        </div>
        <!-- End Legend Indicators -->
      </div>
      <!-- End Col -->
    </div>
    <!-- End Row -->

    <!-- Bar Chart -->
    <div class="chartjs-custom" style="height: 18rem;">
      <canvas id="updatingLineChart"
              data-hs-chartjs-options='{
                "type": "line",
                "data": {
                   "labels": ["Feb","Jan","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                   "datasets": [{
                    "backgroundColor": ["rgba(55, 125, 255, .5)", "rgba(255, 255, 255, .2)"],
                    "borderColor": "#377dff",
                    "borderWidth": 2,
                    "pointRadius": 0,
                    "hoverBorderColor": "#377dff",
                    "pointBackgroundColor": "#377dff",
                    "pointBorderColor": "#fff",
                    "pointHoverRadius": 0,
                    "tension": 0.4
                  },
                  {
                    "backgroundColor": ["rgba(0, 201, 219, .5)", "rgba(255, 255, 255, .2)"],
                    "borderColor": "#00c9db",
                    "borderWidth": 2,
                    "pointRadius": 0,
                    "hoverBorderColor": "#00c9db",
                    "pointBackgroundColor": "#00c9db",
                    "pointBorderColor": "#fff",
                    "pointHoverRadius": 0,
                    "tension": 0.4
                  }]
                },
                "options": {
                  "gradientPosition": {"y1": 200},
                   "scales": {
                      "y": {
                        "grid": {
                          "color": "#e7eaf3",
                          "drawBorder": false,
                          "zeroLineColor": "#e7eaf3"
                        },
                        "ticks": {
                          "min": 0,
                          "max": 100,
                          "stepSize": 20,
                          "fontColor": "#97a4af",
                          "fontFamily": "Open Sans, sans-serif",
                          "padding": 10,
                          "postfix": "k"
                        }
                      },
                      "x": {
                        "grid": {
                          "display": false,
                          "drawBorder": false
                        },
                        "ticks": {
                          "fontSize": 12,
                          "fontColor": "#97a4af",
                          "fontFamily": "Open Sans, sans-serif",
                          "padding": 5
                        }
                      }
                  },
                  "plugins": {
                    "tooltip": {
                      "prefix": "$",
                      "postfix": "k",
                      "hasIndicator": true,
                      "mode": "index",
                      "intersect": false,
                      "lineMode": true,
                      "lineWithLineColor": "rgba(19, 33, 68, 0.075)"
                    }
                  },
                  "hover": {
                    "mode": "nearest",
                    "intersect": true
                  }
                }
              }'>
      </canvas>
    </div>
    <!-- End Bar Chart -->
  </div>
  <!-- End Body -->

  <!-- Table -->
  <div class="table-responsive">
    <table class="table table-borderless table-thead-bordered table-align-middle card-table">
      <thead class="thead-light">
        <tr>
          <th>Project name</th>
          <th>Members</th>
          <th>Spent</th>
          <th>Hours</th>
          <th>Completion</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>
            <a class="d-flex align-items-center" href="#">
              <div class="flex-shrink-0">
                <img class="avatar avatar-sm" src="{{ asset('front-dashboard-v2.1.1/src/assets/svg/brands/spec-icon.svg') }}" alt="Image Description">
              </div>
              <div class="flex-grow-1 ms-3">
                <span class="d-block h5 text-inherit mb-0">Install Front pay</span>
              </div>
            </a>
          </td>
          <td>
            <!-- Avatar Group -->
            <div class="avatar-group avatar-group-xs avatar-circle">
              <a class="avatar" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Amanda Harvey">
                <img class="avatar-img" src="{{ asset('front-dashboard-v2.1.1/src/assets/img/160x160/img10.jpg') }}" alt="Image Description">
              </a>
              <a class="avatar" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="David Harrison">
                <img class="avatar-img" src="{{ asset('front-dashboard-v2.1.1/src/assets/img/160x160/img3.jpg') }}" alt="Image Description">
              </a>
              <a class="avatar avatar-soft-info" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Lisa Iston">
                <span class="avatar-initials">L</span>
              </a>
              <a class="avatar avatar-light avatar-circle" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Lewis Clarke, Chris Mathew and 3 more">
                <span class="avatar-initials">+5</span>
              </a>
            </div>
            <!-- End Avatar Group -->
          </td>
          <td>
            <div class="d-flex align-items-center">
              <span class="mb-0">$25,000</span>
            </div>
          </td>
          <td>
            <div class="d-flex align-items-center">
              <span class="mb-0">34</span>
              <span class="badge bg-soft-danger text-danger p-1 ms-2">
                <i class="bi-graph-down"></i> 1.8
              </span>
            </div>
          </td>
          <td>
            <div class="d-flex align-items-center">
              <span class="mb-0 me-2">26%</span>
              <div class="progress table-progress">
                <div class="progress-bar" role="progressbar" style="width: 26%" aria-valuenow="26" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <a class="d-flex align-items-center" href="#">
              <div class="flex-shrink-0">
                <img class="avatar avatar-sm" src="{{ asset('front-dashboard-v2.1.1/src/assets/svg/brands/mailchimp-icon.svg') }}" alt="Image Description">
              </div>
              <div class="flex-grow-1 ms-3">
                <span class="d-block h5 text-inherit mb-0">Update subscription method <i class="bi-patch-check-fill text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Earned extra bonus"></i></span>
              </div>
            </a>
          </td>
          <td>
            <!-- Avatar Group -->
            <div class="avatar-group avatar-group-xs avatar-circle">
              <a class="avatar" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Costa Quinn">
                <img class="avatar-img" src="{{ asset('front-dashboard-v2.1.1/src/assets/img/160x160/img6.jpg') }}" alt="Image Description">
              </a>
              <a class="avatar" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Clarice Boone">
                <img class="avatar-img" src="{{ asset('front-dashboard-v2.1.1/src/assets/img/160x160/img7.jpg') }}" alt="Image Description">
              </a>
              <a class="avatar avatar-soft-danger" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Adam Keep">
                <span class="avatar-initials">A</span>
              </a>
            </div>
            <!-- End Avatar Group -->
          </td>
          <td>
            <div class="d-flex align-items-center">
              <span class="mb-0">$5,783</span>
              <span class="badge bg-soft-success text-success p-1 ms-2">
                <i class="bi-graph-up"></i> 7.3%
              </span>
            </div>
          </td>
          <td>
            <div class="d-flex align-items-center">
              <span class="mb-0">73.1</span>
              <span class="badge bg-soft-success text-success p-1 ms-2">
                <i class="bi-graph-up"></i> 5.0
              </span>
            </div>
          </td>
          <td>
            <div class="d-flex align-items-center">
              <span class="mb-0 me-2">100%</span>
              <div class="progress table-progress">
                <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <a class="d-flex align-items-center" href="#">
              <div class="flex-shrink-0">
                <div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                  <span class="avatar-initials">I</span>
                </div>
              </div>
              <div class="flex-grow-1 ms-3">
                <span class="d-block h5 text-inherit mb-0">Increase productivity with reviews</span>
              </div>
            </a>
          </td>
          <td>
            <!-- Avatar Group -->
            <div class="avatar-group avatar-group-xs avatar-circle">
              <a class="avatar" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Finch Hoot">
                <img class="avatar-img" src="{{ asset('front-dashboard-v2.1.1/src/assets/img/160x160/img5.jpg') }}" alt="Image Description">
              </a>
              <a class="avatar avatar-soft-dark" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Bob Bardly">
                <span class="avatar-initials">B</span>
              </a>
              <a class="avatar" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Linda Bates">
                <img class="avatar-img" src="{{ asset('front-dashboard-v2.1.1/src/assets/img/160x160/img8.jpg') }}" alt="Image Description">
              </a>
              <a class="avatar" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Ella Lauda">
                <img class="avatar-img" src="{{ asset('front-dashboard-v2.1.1/src/assets/img/160x160/img9.jpg') }}" alt="Image Description">
              </a>
            </div>
            <!-- End Avatar Group -->
          </td>
          <td>
            <div class="d-flex align-items-center">
              <span class="mb-0">$36,678</span>
            </div>
          </td>
          <td>
            <div class="d-flex align-items-center">
              <span class="mb-0">68.7</span>
            </div>
          </td>
          <td>
            <div class="d-flex align-items-center">
              <span class="mb-0 me-2">7%</span>
              <div class="progress table-progress">
                <div class="progress-bar" role="progressbar" style="width: 7%" aria-valuenow="7" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <!-- End Table -->

  <!-- Card Footer -->
  <a class="card-footer text-center" href="#">
    View all projects <i class="bi-chevron-right"></i>
  </a>
  <!-- End Card Footer -->
</div>
<!-- End Card -->

<div class="row">
  <div class="col-lg-4 mb-3 mb-lg-5">
    <!-- Card -->
    <div class="card h-100">
      <!-- Header -->
      <div class="card-header card-header-content-between">
        <h4 class="card-header-title">Payments</h4>

        <!-- Dropdown -->
        <div class="dropdown">
          <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle" id="reportsOverviewDropdown1" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi-three-dots-vertical"></i>
          </button>

          <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="reportsOverviewDropdown1">
            <span class="dropdown-header">Settings</span>
            <a class="dropdown-item" href="#">
              <i class="bi-share-fill dropdown-item-icon"></i> Share reports
            </a>
            <a class="dropdown-item" href="#">
              <i class="bi-download dropdown-item-icon"></i> Download
            </a>
            <a class="dropdown-item" href="#">
              <i class="bi-alt dropdown-item-icon"></i> Connect other apps
            </a>
            <div class="dropdown-divider"></div>
            <span class="dropdown-header">Feedback</span>
            <a class="dropdown-item" href="#">
              <i class="bi-chat-left-dots dropdown-item-icon"></i> Report
            </a>
          </div>
        </div>
        <!-- End Dropdown -->
      </div>
      <!-- End Header -->

      <!-- Body -->
      <div class="card-body text-center">
        <!-- Badge -->
        <div class="h3">
          <span class="badge bg-soft-info text-info rounded-pill">
            <i class="bi-check-circle-fill me-1"></i> On track
          </span>
        </div>
        <!-- End Badge -->

        <!-- Chart Half -->
        <div class="chartjs-doughnut-custom" style="height: 12rem;">
          <canvas id="doughnutHalfChart" class="js-chartjs-doughnut-half"
                  data-hs-chartjs-options='{
                  "type": "doughnut",
                  "data": {
                    "labels": ["Current status", "Goal"],
                    "datasets": [{
                      "data": [64, 35],
                      "backgroundColor": ["#377dff", "rgba(55, 125, 255, .35)"],
                      "borderWidth": 4,
                      "borderColor": "#fff",
                      "hoverBorderColor": "#ffffff"
                    }]
                  }
                }'></canvas>

          <div class="chartjs-doughnut-custom-stat">
            <small class="text-cap">Project balance</small>
            <span class="h1">$150,238.00</span>
          </div>
        </div>
        <!-- End Chart Half -->

        <hr>

        <div class="row col-divider">
          <div class="col text-end">
            <span class="d-block h4 mb-0">$72.46</span>
            <span class="d-block">last transaction</span>
          </div>

          <div class="col text-start">
            <span class="d-block h4 text-success mb-0">
              <i class="bi-graph-up"></i> 12%
            </span>
            <span class="d-block">since last visit</span>
          </div>
        </div>
        <!-- End Row -->
      </div>
      <!-- End Body -->
    </div>
    <!-- End Card -->
  </div>

  <div class="col-lg-8 mb-3 mb-lg-5">
    <!-- Card -->
    <div class="card h-100">
      <!-- Header -->
      <div class="card-header card-header-content-between">
        <h4 class="card-header-title">Latest transactions</h4>

        <!-- Dropdown -->
        <div class="dropdown">
          <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle" id="reportsOverviewDropdown3" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi-three-dots-vertical"></i>
          </button>

          <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="reportsOverviewDropdown3">
            <span class="dropdown-header">Settings</span>
            <a class="dropdown-item" href="#">
              <i class="bi-share-fill dropdown-item-icon"></i> Share reports
            </a>
            <a class="dropdown-item" href="#">
              <i class="bi-download dropdown-item-icon"></i> Download
            </a>
            <a class="dropdown-item" href="#">
              <i class="bi-alt dropdown-item-icon"></i> Connect other apps
            </a>
            <div class="dropdown-divider"></div>
            <span class="dropdown-header">Feedback</span>
            <a class="dropdown-item" href="#">
              <i class="bi-chat-left-dots dropdown-item-icon"></i> Report
            </a>
          </div>
        </div>
        <!-- End Dropdown -->
      </div>
      <!-- End Header -->

      <!-- Body -->
      <div class="card-body card-body-height">
        <ul class="list-group list-group-flush list-group-no-gutters">
          <!-- List Item -->
          <li class="list-group-item">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <!-- Avatar -->
                <div class="avatar avatar-sm avatar-soft-dark avatar-circle">
                  <span class="avatar-initials">B</span>
                </div>
                <!-- End Avatar -->
              </div>

              <div class="flex-grow-1 ms-3">
                <div class="row">
                  <div class="col-7 col-md-5 order-md-1">
                    <h5 class="mb-0">Bob Dean</h5>
                    <span class="fs-6 text-body">Transfer to bank account</span>
                  </div>

                  <div class="col-5 col-md-4 order-md-3 text-end mt-2 mt-md-0">
                    <h5 class="mb-0">-$290.00 USD</h5>
                    <span class="fs-6 text-body">15 May, 2020</span>
                  </div>

                  <div class="col-auto col-md-3 order-md-2">
                    <span class="badge bg-soft-warning text-warning rounded-pill">Pending</span>
                  </div>
                </div>
                <!-- End Row -->
              </div>
            </div>
          </li>
          <!-- End List Item -->

          <!-- List Item -->
          <li class="list-group-item">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <!-- Avatar -->
                <img class="avatar avatar-sm avatar-circle" src="{{ asset('front-dashboard-v2.1.1/src/assets/svg/brands/slack-icon.svg') }}" alt="Image Description">
                <!-- End Avatar -->
              </div>

              <div class="flex-grow-1 ms-3">
                <div class="row">
                  <div class="col-7 col-md-5 order-md-1">
                    <h5 class="mb-0">Slack</h5>
                    <span class="fs-6 text-body">Subscription payment</span>
                  </div>

                  <div class="col-5 col-md-4 order-md-3 text-end mt-2 mt-md-0">
                    <h5 class="mb-0">-$11.00 USD</h5>
                    <span class="fs-6 text-body">12 May, 2020</span>
                  </div>

                  <div class="col-auto col-md-3 order-md-2">
                    <span class="badge bg-soft-success text-success rounded-pill">Completed</span>
                  </div>
                </div>
                <!-- End Row -->
              </div>
            </div>
          </li>
          <!-- End List Item -->

          <!-- List Item -->
          <li class="list-group-item">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <!-- Avatar -->
                <img class="avatar avatar-sm avatar-circle" src="{{ asset('front-dashboard-v2.1.1/src/assets/svg/brands/bank-of-america-icon.svg') }}" alt="Image Description">
                <!-- End Avatar -->
              </div>

              <div class="flex-grow-1 ms-3">
                <div class="row">
                  <div class="col-7 col-md-5 order-md-1">
                    <h5 class="mb-0">Bank of America</h5>
                    <span class="fs-6 text-body">Withdrawal to bank account</span>
                  </div>

                  <div class="col-5 col-md-4 order-md-3 text-end mt-2 mt-md-0">
                    <h5 class="text-success mb-0">$3500.00 USD</h5>
                    <span class="fs-6 text-body">10 May, 2020</span>
                  </div>

                  <div class="col-auto col-md-3 order-md-2">
                    <span class="badge bg-soft-success text-success rounded-pill">Completed</span>
                  </div>
                </div>
                <!-- End Row -->
              </div>
            </div>
          </li>
          <!-- End List Item -->
        </ul>
      </div>
      <!-- End Body -->
    </div>
    <!-- End Card -->
  </div>
</div>
<!-- End Row -->

<!-- Card -->
<div class="card mb-3 mb-lg-5">
  <!-- Header -->
  <div class="card-header card-header-content-between">
    <h4 class="card-header-title">Audience overview <i class="bi-patch-check-fill text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="This report is based on 100% of sessions."></i></h4>

    <!-- Dropdown -->
    <div class="dropdown">
      <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle" id="reportsOverviewDropdown2" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi-three-dots-vertical"></i>
      </button>

      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="reportsOverviewDropdown2">
        <span class="dropdown-header">Settings</span>
        <a class="dropdown-item" href="#">
          <i class="bi-share-fill dropdown-item-icon"></i> Share reports
        </a>
        <a class="dropdown-item" href="#">
          <i class="bi-download dropdown-item-icon"></i> Download
        </a>
        <a class="dropdown-item" href="#">
          <i class="bi-alt dropdown-item-icon"></i> Connect other apps
        </a>
        <div class="dropdown-divider"></div>
        <span class="dropdown-header">Feedback</span>
        <a class="dropdown-item" href="#">
          <i class="bi-chat-left-dots dropdown-item-icon"></i> Report
        </a>
      </div>
    </div>
    <!-- End Dropdown -->
  </div>
  <!-- End Header -->

  <!-- Body -->
  <div class="card-body">
    <div class="row col-sm-divider">
      <div class="col-sm-3">
        <!-- Stats -->
        <div class="d-lg-flex align-items-md-center">
          <div class="flex-shrink-0">
            <i class="bi-person fs-1 text-primary"></i>
          </div>

          <div class="flex-grow-1 ms-lg-3">
            <span class="d-block fs-6">Users</span>
            <div class="d-flex align-items-center">
              <h3 class="mb-0">34,413</h3>
              <span class="badge bg-soft-success text-success ms-2">
                <i class="bi-graph-up"></i> 12.5%
              </span>
            </div>
          </div>
        </div>
        <!-- End Stats -->
      </div>

      <div class="col-sm-3">
        <!-- Stats -->
        <div class="d-lg-flex align-items-md-center">
          <div class="flex-shrink-0">
            <i class="bi-clock-history fs-1 text-primary"></i>
          </div>

          <div class="flex-grow-1 ms-lg-3">
            <span class="d-block fs-6">Avg. session duration</span>
            <div class="d--flex align-items-center">
              <h3 class="mb-0">1m 3s</h3>
            </div>
          </div>
        </div>
        <!-- End Stats -->
      </div>

      <div class="col-sm-3">
        <!-- Stats -->
        <div class="d-lg-flex align-items-md-center">
          <div class="flex-shrink-0">
            <i class="bi-files-alt fs-1 text-primary"></i>
          </div>

          <div class="flex-grow-1 ms-lg-3">
            <span class="d-block fs-6">Pages/Sessions</span>
            <div class="d--flex align-items-center">
              <h3 class="mb-0">1.78</h3>
            </div>
          </div>
        </div>
        <!-- End Stats -->
      </div>

      <div class="col-sm-3">
        <!-- Stats -->
        <div class="d-lg-flex align-items-md-center">
          <div class="flex-shrink-0">
            <i class="bi-pie-chart fs-1 text-primary"></i>
          </div>

          <div class="flex-grow-1 ms-lg-3">
            <span class="d-block fs-6">Bounce rate</span>
            <div class="d--flex align-items-center">
              <h3 class="mb-0">62.9%</h3>
            </div>
          </div>
        </div>
        <!-- End Stats -->
      </div>
    </div>
    <!-- End Row -->
  </div>
  <!-- End Body -->

  <!-- Vector Map -->
  <div class="jsvectormap-custom-wrapper">
    <div class="js-jsvectormap jsvectormap-custom"
          data-hs-js-vector-map-options='{
            "focusOn": {
          "coords": [25, 12],
          "scale": 1.5,
          "animate": true
        },
            "regionStyle": {
              "initial": {
                "fill": "rgba(55, 125, 255, .3)"
              },
              "hover": {
                "fill": "#377dff"
              }
            },
            "markerStyle": {
              "initial": {
                "stroke-width": 2,
                "fill": "rgba(255,255,255,.5)",
                "stroke": "rgba(255,255,255,.5)",
                "r": 6
              },
              "hover": {
                "fill": "#fff",
                "stroke": "#fff"
              }
            }
          }'>
    </div>
  </div>
  <!-- End Vector Map -->
</div>
<!-- End Card -->
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Verificar si HSCore está disponible
    if (typeof HSCore !== 'undefined' && HSCore.components) {
      // INITIALIZATION OF CHARTJS
      var updatingChartDatasets = [
        [
          [18, 51, 60, 38, 88, 50, 40, 52, 88, 80, 60, 70],
          [27, 38, 60, 77, 40, 50, 49, 29, 42, 27, 42, 50]
        ],
        [
          [77, 40, 50, 49, 27, 38, 60, 42, 50, 29, 42, 27],
          [60, 38, 18, 51, 88, 50, 40, 52, 60, 70, 88, 80]
        ]
      ]

      // INITIALIZATION OF CHARTJS
      if (HSCore.components.HSChartJS) {
        HSCore.components.HSChartJS.init(document.querySelector('#updatingLineChart'), {
          data: {
            datasets: [
              {
                data: updatingChartDatasets[0][0]
              },
              {
                data: updatingChartDatasets[0][1]
              }
            ]
          }
        })

        const updatingLineChart = HSCore.components.HSChartJS.getItem(0)

        // Call when tab is clicked
        document.querySelectorAll('[data-bs-toggle="chart"]')
          .forEach($item => {
            $item.addEventListener('click', e => {
              let keyDataset = e.currentTarget.getAttribute('data-datasets')

              // Update datasets for chart
              if (updatingLineChart) {
                updatingLineChart.data.datasets.forEach((dataset, key) => {
                  dataset.data = updatingChartDatasets[keyDataset][key];
                });
                updatingLineChart.update();
              }
            })
          })

        // INITIALIZATION OF DOUGHNUT CHART
        HSCore.components.HSChartJS.init(document.querySelector('.js-chartjs-doughnut-half'), {
          options: {
            plugins: {
              tooltip: {
                postfix: "%"
              }
            },
            cutout: '85%',
            rotation: '270',
            circumference: '180'
          }
        });
      }

      // INITIALIZATION OF VECTOR MAP
      if (HSCore.components.HSJsVectorMap) {
        const markers = [
          {
            "coords": [38, -97],
            "name": "United States",
            "active": 200,
            "new": 40,
            "flag": "{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/flag-icon-css/flags/1x1/us.svg') }}",
            "code": "US"
          },
          {
            "coords": [20, 77],
            "name": "India",
            "active": 300,
            "new": 100,
            "flag": "{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/flag-icon-css/flags/1x1/in.svg') }}",
            "code": "IN"
          },
          {
            "coords": [60, -105],
            "name": "Canada",
            "active": 400,
            "new": 500,
            "flag": "{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/flag-icon-css/flags/1x1/ca.svg') }}",
            "code": "CA"
          }
        ];

        const tooltipTemplate = function (marker) {
          return `
            <span class="d-flex align-items-center mb-2">
              <img class="avatar avatar-xss avatar-circle" src="${marker.flag}" alt="Flag">
              <span class="h5 ms-2 mb-0">${marker.name}</span>
            </span>
            <div class="d-flex justify-content-between" style="max-width: 10rem;">
              <strong>Active:</strong>
              <span class="ms-2">${marker.active}</span>
            </div>
            <div class="d-flex justify-content-between" style="max-width: 10rem;">
              <strong>New:</strong>
              <span class="ms-2">${marker.new}</span>
            </div>
          `;
        };

        HSCore.components.HSJsVectorMap.init('.js-jsvectormap', {
          markers,
          onRegionTooltipShow(map, tooltip, code) {
            let marker = markers.find(function (marker) {
              return marker.code === code;
            });

            if (marker) {
              tooltip._tooltip.style.display = null;
              tooltip._tooltip.innerHTML = tooltipTemplate(marker);
            } else {
              tooltip._tooltip.style.display = 'none';
            }
          },
          onMarkerTooltipShow: function (map, tooltip, code) {
            tooltip._tooltip.style.display = null;
            tooltip._tooltip.innerHTML = tooltipTemplate(markers[code]);
          },
          backgroundColor: '#132144'
        })
      }
    } else {
      console.log('HSCore no está disponible. Verificar que los scripts se estén cargando correctamente.');
    }
  })
</script>
@endsection
