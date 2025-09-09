@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col-sm mb-2 mb-sm-0">
      <h1 class="page-header-title">Dashboard Principal</h1>
      <p class="page-header-text">Panel de control y estadísticas generales de BodegaMultiplex</p>
    </div>
    
  </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Ventas Hoy</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="js-counter display-4 text-primary">Q{{ number_format($stats['ventas_hoy'], 0) }}</span>
          </div>
          <div class="col-auto">
            <i class="bi-graph-up text-success" style="font-size: 2rem;"></i>
          </div>
        </div>
        <small class="text-muted">Ingresos del día</small>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Productos Vendidos</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="js-counter display-4 text-info">{{ $stats['productos_vendidos_hoy'] }}</span>
          </div>
          <div class="col-auto">
            <i class="bi-box-seam text-info" style="font-size: 2rem;"></i>
          </div>
        </div>
        <small class="text-muted">Unidades hoy</small>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Clientes Atendidos</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="js-counter display-4 text-warning">{{ $stats['clientes_atendidos_hoy'] }}</span>
          </div>
          <div class="col-auto">
            <i class="bi-people text-warning" style="font-size: 2rem;"></i>
          </div>
        </div>
        <small class="text-muted">Clientes únicos hoy</small>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="card-subtitle mb-2">Ganancia Neta Mes</h6>
        <div class="row align-items-center gx-2">
          <div class="col">
            <span class="js-counter display-4 text-success">Q{{ number_format($stats['ganancia_neta_mes'], 0) }}</span>
          </div>
          <div class="col-auto">
            <i class="bi-graph-up text-success" style="font-size: 2rem;"></i>
          </div>
        </div>
        <small class="text-muted">Estimado 30% margen</small>
      </div>
    </div>
  </div>
</div>
<!-- End Stats Cards -->

<!-- SECCIÓN 1: GRÁFICAS DE ÓRDENES -->
<div class="row mb-4">
  <div class="col-12">
    <h3 class="mb-3">📊 Análisis de Órdenes</h3>
  </div>
  
  <!-- Órdenes por Estado -->
  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h4 class="card-title">Órdenes por Estado</h4>
      </div>
      <div class="card-body">
        <canvas id="ordenesPorEstadoChart" style="max-height: 300px;"></canvas>
      </div>
    </div>
  </div>

  <!-- Métodos de Pago -->
  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h4 class="card-title">Métodos de Pago</h4>
              </div>
      <div class="card-body">
        <canvas id="metodosPagoChart" style="max-height: 300px;"></canvas>
              </div>
            </div>
  </div>

  <!-- Órdenes por Día -->
  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h4 class="card-title">Órdenes Últimos 7 días</h4>
          </div>
      <div class="card-body">
        <canvas id="ordenesPorDiaChart" style="max-height: 300px;"></canvas>
      </div>
        </div>
          </div>
        </div>

<!-- SECCIÓN 2: GRÁFICAS DE PRODUCTOS -->
<div class="row mb-4">
  <div class="col-12">
    <h3 class="mb-3">📦 Análisis de Productos e Inventario</h3>
          </div>

  <!-- Productos Más Vendidos -->
  <div class="col-lg-3 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h4 class="card-title">Productos Más Vendidos</h4>
          </div>
      <div class="card-body">
        <canvas id="productosMasVendidosChart" style="max-height: 300px;"></canvas>
      </div>
    </div>
  </div>

  <!-- Productos por Categoría -->
  <div class="col-lg-3 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h4 class="card-title">Productos por Categoría</h4>
      </div>
      <div class="card-body">
        <canvas id="productosPorCategoriaChart" style="max-height: 300px;"></canvas>
              </div>
            </div>
              </div>

  <!-- Estado del Stock -->
  <div class="col-lg-3 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h4 class="card-title">Estado del Stock</h4>
                  </div>
      <div class="card-body">
        <canvas id="productosEstadoStockChart" style="max-height: 300px;"></canvas>
              </div>
            </div>
              </div>

  <!-- Inventario & Alertas -->
  <div class="col-lg-3 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h4 class="card-title">Estado del Inventario</h4>
                  </div>
      <div class="card-body">
        <div class="mb-4 text-center">
          <h3 class="text-primary">Q{{ number_format($dashboardData['valor_inventario'], 0) }}</h3>
          <small class="text-muted">Valor Total del Inventario</small>
                  </div>

        <h6 class="mb-3">⚠️ Stock Crítico</h6>
        @forelse($dashboardData['stock_bajo'] as $producto)
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-truncate">{{ Str::limit($producto->nombre, 15) }}</span>
          <span class="badge {{ $producto->stock_actual <= 5 ? 'bg-danger' : 'bg-warning' }}">
            {{ $producto->stock_actual }}
          </span>
                  </div>
        @empty
        <p class="text-success text-center">✅ Stock en buenos niveles</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

<!-- SECCIÓN 3: ANÁLISIS AVANZADO DE PRODUCTOS -->
<div class="row mb-4">
  <!-- Productos Más Valiosos -->
  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h4 class="card-title">Productos Más Valiosos</h4>
      </div>
      <div class="card-body">
        <canvas id="productosMasValiososChart" style="max-height: 300px;"></canvas>
      </div>
    </div>
  </div>

  <!-- Análisis de Precios -->
  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h4 class="card-title">Análisis de Precios</h4>
      </div>
  <div class="card-body">
        <div class="row text-center">
          <div class="col-6 mb-3">
            <small class="text-muted">Precio Promedio</small>
            <h4 class="text-primary">Q{{ number_format($chartData['analisis_precios']['precio_promedio'], 0) }}</h4>
          </div>
          <div class="col-6 mb-3">
            <small class="text-muted">Precio Máximo</small>
            <h4 class="text-success">Q{{ number_format($chartData['analisis_precios']['precio_max'], 0) }}</h4>
          </div>
          <div class="col-6">
            <small class="text-muted">Productos Caros</small>
            <h5 class="text-warning">{{ $chartData['analisis_precios']['productos_caros'] }}</h5>
            <small class="text-muted">> Q100</small>
          </div>
          <div class="col-6">
            <small class="text-muted">Productos Baratos</small>
            <h5 class="text-info">{{ $chartData['analisis_precios']['productos_baratos'] }}</h5>
            <small class="text-muted">≤ Q10</small>
          </div>
        </div>
      </div>
    </div>
          </div>

  <!-- Productos Próximos a Vencer -->
  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h4 class="card-title">⏰ Próximos a Vencer</h4>
            </div>
      <div class="card-body">
        @forelse($chartData['productos_proximos_vencer'] as $producto)
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-truncate">{{ Str::limit($producto->nombre, 20) }}</span>
          <span class="badge {{ $producto->dias_restantes <= 7 ? 'bg-danger' : 'bg-warning' }}">
            {{ $producto->dias_restantes }}d
          </span>
        </div>
        @empty
        <p class="text-success text-center">✅ No hay productos próximos a vencer</p>
        @endforelse
      </div>
            </div>
          </div>
        </div>

<!-- Ventas Mensuales -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">📈 Ventas Mensuales (Últimos 12 meses)</h4>
      </div>
      <div class="card-body">
        <canvas id="ventasMensualesChart" style="max-height: 400px;"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Tendencia de Órdenes -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">📊 Tendencia de Órdenes (Últimos 30 días)</h4>
      </div>
      <div class="card-body">
        <canvas id="tendenciaOrdenesChart" style="max-height: 300px;"></canvas>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Configuración de colores
    const chartColors = {
        primary: '#377dff',
        success: '#00c9a7',
        info: '#00d4ff',
        warning: '#ffab00',
        danger: '#de4437',
        secondary: '#77838f',
        light: '#f8f9fa',
        dark: '#132144'
    };

    // Datos desde el backend
    const chartData = @json($chartData);

    // ===== GRÁFICAS DE ÓRDENES =====
    
    // Gráfica de Órdenes por Estado
    const ordenesPorEstadoCtx = document.getElementById('ordenesPorEstadoChart').getContext('2d');
    new Chart(ordenesPorEstadoCtx, {
        type: 'doughnut',
          data: {
            labels: Object.keys(chartData.ordenes_por_estado).map(estado => {
                const estados = {
                    'pendiente': 'Pendiente',
                    'confirmado': 'Confirmado',
                    'en_preparacion': 'En Preparación',
                    'proceso': 'En Proceso',
                    'enviado': 'Enviado',
                    'entregado': 'Entregado',
                    'completado': 'Completado',
                    'cancelado': 'Cancelado'
                };
                return estados[estado] || estado;
            }),
            datasets: [{
                data: Object.values(chartData.ordenes_por_estado),
                backgroundColor: [
                    chartColors.warning,
                    chartColors.info,
                    chartColors.primary,
                    chartColors.warning,
                    chartColors.secondary,
                    chartColors.success,
                    chartColors.success,
                    chartColors.danger
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfica de Métodos de Pago
    const metodosPagoCtx = document.getElementById('metodosPagoChart').getContext('2d');
    new Chart(metodosPagoCtx, {
        type: 'pie',
        data: {
            labels: Object.keys(chartData.metodos_pago),
            datasets: [{
                data: Object.values(chartData.metodos_pago),
                backgroundColor: [
                    chartColors.success,
                    chartColors.primary,
                    chartColors.info,
                    chartColors.warning
                ]
            }]
        },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfica de Órdenes por Día
    const ordenesPorDiaCtx = document.getElementById('ordenesPorDiaChart').getContext('2d');
    new Chart(ordenesPorDiaCtx, {
        type: 'bar',
        data: {
            labels: chartData.ordenes_por_dia.map(item => item.dia),
            datasets: [{
                label: 'Órdenes',
                data: chartData.ordenes_por_dia.map(item => item.total),
                backgroundColor: chartColors.info,
                borderColor: chartColors.info,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // ===== GRÁFICAS DE PRODUCTOS =====

    // Gráfica de Productos Más Vendidos
    if (chartData.productos_mas_vendidos.length > 0) {
        const productosMasVendidosCtx = document.getElementById('productosMasVendidosChart').getContext('2d');
        new Chart(productosMasVendidosCtx, {
            type: 'bar',
            data: {
                labels: chartData.productos_mas_vendidos.slice(0, 5).map(item => item.nombre.substring(0, 15) + '...'),
                datasets: [{
                    label: 'Cantidad Vendida',
                    data: chartData.productos_mas_vendidos.slice(0, 5).map(item => item.total_vendido),
                    backgroundColor: chartColors.info,
                    borderColor: chartColors.info,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
          }
        });
      }

    // Gráfica de Productos por Categoría
    if (Object.keys(chartData.productos_por_categoria).length > 0) {
        const productosPorCategoriaCtx = document.getElementById('productosPorCategoriaChart').getContext('2d');
        new Chart(productosPorCategoriaCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(chartData.productos_por_categoria),
                datasets: [{
                    data: Object.values(chartData.productos_por_categoria),
                    backgroundColor: [
                        chartColors.primary,
                        chartColors.success,
                        chartColors.warning,
                        chartColors.info,
                        chartColors.danger,
                        chartColors.secondary
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Gráfica de Estado del Stock
    const productosEstadoStockCtx = document.getElementById('productosEstadoStockChart').getContext('2d');
    new Chart(productosEstadoStockCtx, {
        type: 'doughnut',
        data: {
            labels: ['Crítico (≤5)', 'Bajo (6-10)', 'Normal (11-50)', 'Alto (>50)', 'Agotado (0)'],
            datasets: [{
                data: [
                    chartData.productos_estado_stock.critico,
                    chartData.productos_estado_stock.bajo,
                    chartData.productos_estado_stock.normal,
                    chartData.productos_estado_stock.alto,
                    chartData.productos_estado_stock.agotado
                ],
                backgroundColor: [
                    chartColors.danger,
                    chartColors.warning,
                    chartColors.success,
                    chartColors.primary,
                    chartColors.secondary
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfica de Productos Más Valiosos
    if (chartData.productos_mas_valiosos.length > 0) {
        const productosMasValiososCtx = document.getElementById('productosMasValiososChart').getContext('2d');
        new Chart(productosMasValiososCtx, {
            type: 'bar',
            data: {
                labels: chartData.productos_mas_valiosos.slice(0, 5).map(item => item.nombre.substring(0, 15) + '...'),
                datasets: [{
                    label: 'Valor en Inventario (Q)',
                    data: chartData.productos_mas_valiosos.slice(0, 5).map(item => item.valor_total),
                    backgroundColor: chartColors.success,
                    borderColor: chartColors.success,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Q' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Gráfica de Ventas Mensuales Principal
    const ventasMensualesCtx = document.getElementById('ventasMensualesChart').getContext('2d');
    new Chart(ventasMensualesCtx, {
        type: 'line',
        data: {
            labels: chartData.ventas_por_mes.map(item => item.mes),
            datasets: [{
                label: 'Ventas (Q)',
                data: chartData.ventas_por_mes.map(item => item.ventas),
                borderColor: chartColors.primary,
                backgroundColor: chartColors.primary + '20',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Q' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Gráfica de Tendencia de Órdenes
    const tendenciaOrdenesCtx = document.getElementById('tendenciaOrdenesChart').getContext('2d');
    new Chart(tendenciaOrdenesCtx, {
        type: 'line',
        data: {
            labels: chartData.tendencia_ordenes.map(item => item.fecha),
            datasets: [{
                label: 'Órdenes',
                data: chartData.tendencia_ordenes.map(item => item.total),
                borderColor: chartColors.success,
                backgroundColor: chartColors.success + '20',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

});
</script>
@endsection
