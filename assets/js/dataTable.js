document.addEventListener('DOMContentLoaded', () => {
    const inputFecha = document.getElementById('fecha_filtro');
    const formFiltro = document.getElementById('formFiltro');
    const tablaCuerpo = document.getElementById('tablaCuerpo');
    const spanFechaDatos = document.getElementById('fecha_datos_mostrada');
    const btnExportarPdf = document.getElementById('btnExportarPdf');
    const btnExportarExcel = document.getElementById('btnExportarExcel');

    // Variable donde guardamos los datos devueltos por el servidor
    let datosActuales = [];

    // Inicializar input con fecha actual YYYY-MM-DD
    const hoy = new Date().toISOString().split('T')[0];
    if (inputFecha && !inputFecha.value) {
        inputFecha.value = hoy;
    }

    function validarFechaConDatos(fechaDatos) {
        if (!inputFecha) return;

        if (!fechaDatos) {
            inputFecha.style.removeProperty('border');
            inputFecha.style.removeProperty('color');
            inputFecha.classList.remove('is-invalid');
            if (spanFechaDatos) {
                spanFechaDatos.textContent = '--/--/----';
                spanFechaDatos.className = 'text-light';
            }
            return;
        }

        const fechaDatosNormalizada = String(fechaDatos).split(' ')[0].split('T')[0].trim();
        const fechaInput = inputFecha.value.trim();

        const partes = fechaDatosNormalizada.split('-');
        const fechaFormateada = partes.length === 3 ? `${partes[2]}/${partes[1]}/${partes[0]}` : fechaDatosNormalizada;

        if (spanFechaDatos) {
            spanFechaDatos.textContent = fechaFormateada;
        }

        if (fechaInput !== fechaDatosNormalizada) {
            inputFecha.classList.add('is-invalid');
            inputFecha.style.setProperty('border', '2px solid #ef4444', 'important');
            inputFecha.style.setProperty('color', '#ef4444', 'important');

            if (spanFechaDatos) {
                spanFechaDatos.className = 'text-danger';
            }
        } else {
            inputFecha.classList.remove('is-invalid');
            inputFecha.style.removeProperty('border');
            inputFecha.style.removeProperty('color');

            if (spanFechaDatos) {
                spanFechaDatos.className = 'text-success';
            }
        }
    }

    function formatearNumero(valor) {
        if (valor === null || valor === undefined) return '0,00';
        return Number(valor).toLocaleString('es-ES', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    async function cargarLecturas(fecha) {
        tablaCuerpo.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    Cargando datos...
                </td>
            </tr>`;

        try {
            const response = await fetch(`../api/depositocorteprocess.php?fecha_filtro=${encodeURIComponent(fecha)}`);
            const res = await response.json();

            if (!res.exito) {
                datosActuales = [];
                validarFechaConDatos(null);
                tablaCuerpo.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-danger py-4">
                            ${res.mensaje || 'Hubo un problema al cargar los datos.'}
                        </td>
                    </tr>`;
                return;
            }

            datosActuales = res.datos || [];
            renderizarFilas(datosActuales);

        } catch (error) {
            datosActuales = [];
            validarFechaConDatos(null);
            tablaCuerpo.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger py-4">
                        Error de conexión con el servidor.
                    </td>
                </tr>`;
        }
    }

    function renderizarFilas(filas) {
        if (!filas || filas.length === 0) {
            validarFechaConDatos(null);
            tablaCuerpo.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        No hay lecturas registradas para la fecha seleccionada.
                    </td>
                </tr>`;
            return;
        }

        validarFechaConDatos(filas[0].fecha_actual);

        let html = '';
        filas.forEach(row => {
            const valorRaw = String(row.diferencia_kilos ?? '0').replace(',', '.');
            const dif = parseFloat(valorRaw) || 0;

            let estiloColor = 'color: #94a3b8;';
            let signo = '';

            if (dif > 0) {
                estiloColor = 'color: #4ade80 !important; font-weight: 700;';
                signo = '+';
            } else if (dif < 0) {
                estiloColor = 'color: #f87171 !important; font-weight: 700;';
            }

            html += `
                <tr>
                    <td class="fw-bold text-light">${row.deposito}</td>
                    <td class="text-end font-monospace">${formatearNumero(row.kilos_anterior)}</td>
                    <td class="text-end font-monospace text-light">${formatearNumero(row.kilos_actual)}</td>
                    <td class="text-end font-monospace" style="${estiloColor}">${signo}${formatearNumero(dif)}</td>
                </tr>`;
        });

        //para añadir datos de id, fecha anterior y fecha actual en la tabla de arriba
        //<td class="text-center text-muted">${row.id_actual}</td>
        //<td class="text-center text-muted">${row.fecha_anterior ? row.fecha_anterior : '<span class="text-secondary">N/A</span>'}</td>
        //<td class="text-center text-muted">${row.fecha_actual}</td>

        tablaCuerpo.innerHTML = html;
    }

    // Evento del botón para generar y descargar el PDF
    if (btnExportarPdf) {
        btnExportarPdf.addEventListener('click', () => {
            try {
                if (!datosActuales || datosActuales.length === 0) {
                    alert('No hay datos en la tabla para exportar.');
                    return;
                }

                if (!window.jspdf || !window.jspdf.jsPDF) {
                    alert('No se pudieron cargar las librerías de PDF. Revisa los scripts en el HTML.');
                    return;
                }

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                if (typeof doc.autoTable !== 'function') {
                    alert('Error: El plugin jsPDF-AutoTable no está disponible.');
                    return;
                }

                const fechaFiltro = inputFecha ? inputFecha.value : '';
                const fechaReal = spanFechaDatos ? spanFechaDatos.textContent.trim() : '';

                // Título y metadatos del documento
                doc.setFontSize(16);
                doc.setTextColor(30, 41, 59);
                doc.text('Informe de Corte de Depósitos', 14, 18);

                doc.setFontSize(10);
                doc.setTextColor(100);
                doc.text(`Fecha solicitada: ${fechaFiltro}    |    Fecha real de datos: ${fechaReal}`, 14, 25);

                const encabezados = [['Depósito', 'Kilos Anterior', 'Kilos Actual', 'Diferencia (Kg)']];
                const filasPdf = datosActuales.map(row => {
                    const valorRaw = String(row.diferencia_kilos ?? '0').replace(',', '.');
                    const dif = parseFloat(valorRaw) || 0;
                    const signo = dif > 0 ? '+' : '';

                    return [
                        row.deposito,
                        formatearNumero(row.kilos_anterior),
                        formatearNumero(row.kilos_actual),
                        `${signo}${formatearNumero(dif)}`
                    ];
                });

                // Construcción de la tabla con colores en la columna Diferencia
                doc.autoTable({
                    startY: 30,
                    head: encabezados,
                    body: filasPdf,
                    theme: 'striped',
                    headStyles: {
                        fillColor: [15, 23, 42],
                        textColor: [255, 255, 255],
                        fontStyle: 'bold'
                    },
                    styles: {
                        fontSize: 9,
                        halign: 'right'
                    },
                    columnStyles: {
                        0: { halign: 'left', fontStyle: 'bold' }
                    },
                    didParseCell: function (data) {
                        // Columna 3: Diferencia (Kg)
                        if (data.section === 'body' && data.column.index === 3) {
                            const val = String(data.cell.raw ?? '').trim();
                            if (val.startsWith('+')) {
                                data.cell.styles.textColor = [22, 163, 74]; // Verde
                                data.cell.styles.fontStyle = 'bold';
                            } else if (val.startsWith('-')) {
                                data.cell.styles.textColor = [220, 38, 38]; // Rojo
                                data.cell.styles.fontStyle = 'bold';
                            }
                        }
                    }
                });

                doc.save(`corte_depositos_${fechaFiltro || 'informe'}.pdf`);

            } catch (err) {
                console.error("Error al generar el PDF:", err);
                alert("Ocurrió un error al generar el PDF. Inténtelo de nuevo mas tarde.");
            }
        });

        if (btnExportarExcel) {
            btnExportarExcel.addEventListener('click', () => {
                try {
                    if (!datosActuales || datosActuales.length === 0) {
                        alert('No hay datos en la tabla para exportar.');
                        return;
                    }

                    if (typeof XLSX === 'undefined') {
                        alert('No se pudo cargar la librería para generar Excel.');
                        return;
                    }

                    const fechaFiltro = inputFecha ? inputFecha.value : '';
                    const fechaReal = spanFechaDatos ? spanFechaDatos.textContent.trim() : '';

                    // estructura base filas
                    const hojaDatos = [
                        ['Informe de Corte de Depósitos'],
                        [`Fecha solicitada: ${fechaFiltro}`, `Fecha real de datos: ${fechaReal}`],
                        [], // Fila en blanco
                        ['Depósito', 'Kilos Anterior', 'Kilos Actual', 'Diferencia (Kg)'] // Cabecera tabla (Fila 4)
                    ];

                    // inserta rtegistros
                    datosActuales.forEach(row => {
                        const rawDif = String(row.diferencia_kilos ?? '0').replace(',', '.');
                        const rawAnt = String(row.kilos_anterior ?? '0').replace(',', '.');
                        const rawAct = String(row.kilos_actual ?? '0').replace(',', '.');

                        hojaDatos.push([
                            row.deposito,
                            parseFloat(rawAnt) || 0,
                            parseFloat(rawAct) || 0,
                            parseFloat(rawDif) || 0
                        ]);
                    });

                    // crear libro y hoja
                    const libro = XLSX.utils.book_new();
                    const hoja = XLSX.utils.aoa_to_sheet(hojaDatos);

                    // Anchos de columna
                    hoja['!cols'] = [
                        { wch: 18 }, // Depósito
                        { wch: 16 }, // Kilos Anterior
                        { wch: 16 }, // Kilos Actual
                        { wch: 18 }  // Diferencia
                    ];

                    // estilos visuales
                    // cabecera oscura con letras blancas
                    const estiloCabecera = {
                        font: { bold: true, color: { rgb: "FFFFFF" } },
                        fill: { fgColor: { rgb: "0F172A" } },
                        alignment: { horizontal: "center", vertical: "center" }
                    };

                    // verde clarito con texto verde oscuro
                    const estiloPositivo = {
                        fill: { fgColor: { rgb: "DCFCE7" } },
                        font: { bold: true, color: { rgb: "15803D" } },
                        alignment: { horizontal: "right" }
                    };

                    // rojo clarito con texto rojo oscuro
                    const estiloNegativo = {
                        fill: { fgColor: { rgb: "FEE2E2" } },
                        font: { bold: true, color: { rgb: "B91C1C" } },
                        alignment: { horizontal: "right" }
                    };

                    // Estilo numérico base para columnas B y C
                    const estiloNumero = {
                        alignment: { horizontal: "right" }
                    };

                    // Aplicar estilo a la cabecera (Fila 4 -> A4, B4, C4, D4)
                    ['A4', 'B4', 'C4', 'D4'].forEach(ref => {
                        if (hoja[ref]) hoja[ref].s = estiloCabecera;
                    });

                    // Recorrer las filas de datos y pintar según el valor de Diferencia (Columna D)
                    const filaInicio = 5; // En Excel las filas empiezan en 1 (la 5 es el primer registro)
                    const totalFilas = datosActuales.length;

                    for (let i = 0; i < totalFilas; i++) {
                        const numFila = filaInicio + i;
                        const celdaAnt = hoja[`B${numFila}`];
                        const celdaAct = hoja[`C${numFila}`];
                        const celdaDif = hoja[`D${numFila}`];

                        // Formato de miles y decimales en Excel (#,##0.00)
                        if (celdaAnt) {
                            celdaAnt.z = '#,##0.00';
                            celdaAnt.s = estiloNumero;
                        }
                        if (celdaAct) {
                            celdaAct.z = '#,##0.00';
                            celdaAct.s = estiloNumero;
                        }

                        if (celdaDif) {
                            celdaDif.z = '#,##0.00';
                            const valor = celdaDif.v || 0;

                            if (valor > 0) {
                                celdaDif.s = estiloPositivo;
                            } else if (valor < 0) {
                                celdaDif.s = estiloNegativo;
                            } else {
                                celdaDif.s = estiloNumero;
                            }
                        }
                    }

                    XLSX.utils.book_append_sheet(libro, hoja, 'Corte Depósitos');

                    // descarga el fichero
                    XLSX.writeFile(libro, `corte_depositos_${fechaFiltro || 'informe'}.xlsx`);

                } catch (err) {
                    console.error("Error al generar el Excel:", err);
                    alert("No se pudo generar el archivo Excel en este momento. Inténtelo de nuevo.");
                }
            });
        }
    }

    if (formFiltro) {
        formFiltro.addEventListener('submit', (e) => {
            e.preventDefault();
            cargarLecturas(inputFecha.value);
        });
    }

    cargarLecturas(inputFecha ? inputFecha.value : hoy);
});