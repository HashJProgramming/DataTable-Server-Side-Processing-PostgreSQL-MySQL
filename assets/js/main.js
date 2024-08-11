function initializeDataTable(api, columns) {
    if ($.fn.DataTable.isDataTable("#dataTable")) {
      $("#dataTable").DataTable().destroy();
    }
  
    $("#dataTable").DataTable({
      ajax: api,
      processing: true,
      serverSide: true,
      columns: columns,
      search: {
        return: true,
      },
      layout: {
        topStart: {
          buttons: [
            {
              extend: "collection",
              text: '<i class="fas fa-copy"></i> Print Options',
              buttons: [
                {
                  extend: "print",
                  text: '<i class="text-primary fas fa-copy"></i> Print Selected',
                  title:
                    "HashJProgramming",
                  customize: function (win) {
                    // Add logo and institution information
                    var logoAndInfo =
                      '<div class="row">' +
                      '<div class="col-md-8 col-xl-7 text-center text-primary mx-auto">' +
                      '<img src="assets/img/logo.jpg" width="100em">' +
                      '<h4 class="mt-1"><strong>HashJProgramming</strong></h4>' +
                      '<p class="w-lg-50">HashJProgramming<br>' +
                      "Call No. : <strong>000-0000-0000</strong></p>" +
                      "</div>" +
                      "</div>";
                    $(win.document.body).prepend(logoAndInfo);
                    var table = $(win.document.body).find("table");
                    table.prepend(
                      '<thead><tr><th colspan="15" style="text-align: center; font-size: 10px;"> HashJProgramming </th></tr><tr><th colspan="15" style="text-align: right;" id="dateTimeHeader"> Date: ' +
                        new Date().toLocaleString() +
                        "</th></tr></thead>"
                    );
                    // Style adjustments
                    $(win.document.body)
                      .find("table")
                      .addClass("display")
                      .css("font-size", "9px");
                    $(win.document.body)
                      .find("tr:nth-child(odd) td")
                      .each(function (index) {
                        $(this).css("background-color", "#D0D0D0");
                      });
                    $(win.document.body).find("h1").css({
                      "text-align": "center",
                      "margin-top": "10px",
                      display: "none",
                    });
                  },
                  exportOptions: {
                    modifier: {
                      selected: null,
                    },
                    columns: ":visible",
                  },
                  footer: false,
                },
                {
                  extend: "print",
                  text: '<i class="text-primary fas fa-copy"></i> Print All',
                  title:
                    "HashJProgramming",
                  customize: function (win) {
                    // Add logo 
                    var logoAndInfo =
                      '<div class="row">' +
                      '<div class="col-md-8 col-xl-7 text-center text-primary mx-auto">' +
                      '<img src="assets/img/logo.jpg" width="100em">' +
                      '<h4 class="mt-1"><strong>HashJProgramming</strong></h4>' +
                      '<p class="w-lg-50">HashJProgramming<br>' +
                      "Call No. : <strong>000-0000-0000</strong></p>" +
                      "</div>" +
                      "</div>";
                    $(win.document.body).prepend(logoAndInfo);
                    var table = $(win.document.body).find("table");
                    table.prepend(
                      '<thead><tr><th colspan="15" style="text-align: center; font-size: 10px;"> HashJProgramming </th></tr><tr><th colspan="15" style="text-align: right;" id="dateTimeHeader"> Date: ' +
                        new Date().toLocaleString() +
                        "</th></tr></thead>"
                    );
                    // Style adjustments
                    $(win.document.body)
                      .find("table")
                      .addClass("display")
                      .css("font-size", "9px");
                    $(win.document.body)
                      .find("tr:nth-child(odd) td")
                      .each(function (index) {
                        $(this).css("background-color", "#D0D0D0");
                      });
                    $(win.document.body).find("h1").css({
                      "text-align": "center",
                      "margin-top": "10px",
                      display: "none",
                    });
                  },
                  exportOptions: {
                    modifier: {
                      selected: null,
                    },
                    columns: ":visible",
                  },
                  footer: false,
                },
                {
                  extend: "excel",
                  text: '<i class="text-success fas fa-file-excel"></i> Excel',
                  title:
                    "HashJProgramming",
                  exportOptions: {
                    modifier: {
                      selected: null,
                    },
                    columns: ":visible",
                  },
                  footer: false,
                },
                {
                  extend: "pdf",
                  text: '<i class="text-danger fas fa-file-pdf"></i> PDF',
                  orientation: "landscape",
                  pageSize: "LEGAL",
                  download: "open",
                  title:
                    "HashJProgramming",
                  exportOptions: {
                    modifier: {
                      selected: null,
                    },
                    columns: ":visible",
                  },
                  footer: false,
                  customize: function (doc) {
                    // Add logo
                    doc.header = function () {
                      return [
                        {
                          margin: [0, 0, 0, 6],
                          alignment: "center",
                        //   Add logo here Base64 image
                          image:
                            "data:image/png;base64,",
                          width: 70,
                        },
                      ];
                    };
                    doc.content.splice(
                      0,
                      0,
                      {
                        margin: [0, 20, 0, 6],
                        alignment: "center",
                        text: "HashJProgramming",
                        fontSize: 16,
                        bold: true,
                      },
                      {
                        margin: [0, 0, 0, 6],
                        alignment: "center",
                        text: "HashJProgramming Sample",
                      }
                    );
  
                    // Add header with title and date
  
                    doc.content.splice(0, 0, {
                      margin: [0, 0, 0, 6],
                      alignment: "right",
                      text: "Date: " + new Date().toLocaleString(),
                    });
  
                    // Style adjustments
                    doc.styles.tableBodyEven = {
                      fillColor: "#D0D0D0",
                      border: [false, false, false, true], // Bottom border for even rows
                    };
                    doc.styles.tableBodyOdd = {
                      fillColor: "#F9F9F9",
                      border: [false, false, false, true], // Bottom border for odd rows
                    };
                    doc.styles.tableHeader = {
                      fontSize: 10,
                      bold: true,
                      border: [false, false, false, true], // Bottom border for header row
                    };
                    doc.styles.table = {
                      fontSize: 9,
                      border: [false, false, false, false], // No border for the table itself
                    };
  
                    doc.styles.tableBodyEven = {
                      fillColor: "#D0D0D0",
                      border: [false, false, false, true], // Bottom border for even rows
                    };
                    doc.styles.tableBodyOdd = {
                      fillColor: "#F9F9F9",
                      border: [false, false, false, true], // Bottom border for odd rows
                    };
                    doc.styles.tableHeader = {
                      fontSize: 10,
                      bold: true,
                      border: [false, false, false, true], // Bottom border for header row
                    };
                    doc.styles.table = {
                      fontSize: 9,
                      border: [false, false, false, false], // No border for the table itself
                    };
  
                    doc.content.forEach(function (item) {
                      if (item.table) {
                        item.layout = {
                          fillColor: function (rowIndex, node, columnIndex) {
                            return rowIndex % 2 === 0 ? "#D0D0D0" : "#F9F9F9"; // Alternating row colors
                          },
                          hLineWidth: function (i, node) {
                            return i === 0 || i === node.table.body.length
                              ? 1
                              : 0.5; // Top and bottom lines
                          },
                          vLineWidth: function (i) {
                            return 0.5; // Vertical lines
                          },
                          hLineColor: function (i) {
                            return "#000000"; // Border color
                          },
                          vLineColor: function (i) {
                            return "#000000"; // Border color
                          },
                          paddingLeft: function (i) {
                            return 4; // Cell padding
                          },
                          paddingRight: function (i) {
                            return 4; // Cell padding
                          },
                        };
                      }
                    });
                  },
                },
              ],
            },
            { extend: "colvis", text: '<i class="fas fa-columns"></i> Columns' },
            "pageLength",
          ],
        },
      },
      responsive: {
        details: {
          display: DataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return "Details for " + data[0] + " " + data[1];
            },
          }),
          renderer: DataTable.Responsive.renderer.tableAll({
            tableClass: "table",
          }),
        },
      },
      stateSave: true,
      select: true,
    });
  }