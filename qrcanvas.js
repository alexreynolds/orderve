// Function to generate the canvas drawing of the QR code
// 
// INPUTS:
//		typeNumber: QR Code type number, [1,10]. The higher the type number
//					the more data that can be stored
//		id: The element id of the div where the final QR code drawing will be stored
//		text: The URL that the QR code will link to
//		level: Level of error corection for QR code. Can be L,M,Q,H (ordered from least to most correction)
//		style: Style of QR code (square, circle, rounded rectangle)
//
// ** NOTE: Earlier versions of Internet Explorer (before IE 9) do not support HTML5 canvas
// ** NOTE 2: People should not be using Internet Explorer anyway

function drawqr(typeNumber,id,text,level,style,colordark) {

	// Correction level of QR code
	// Defaulted to Q (second highest level), changed based on user's choice
	var correctionlevel = QRErrorCorrectLevel.Q;
	if (level == 'L') { correctionlevel = QRErrorCorrectLevel.L; }
	else if (level == 'M') { correctionlevel = QRErrorCorrectLevel.M; }
	else if (level == 'Q') { correctionlevel = QRErrorCorrectLevel.Q; }
	else { correctionlevel = QRErrorCorrectLevel.H; }


	// Gets the id for the div where the QR code will be
	var e = document.getElementById(id);

	// Proceed if the div for the QR code exists
	if (e) { 

		// NOTE: later add a canvas emulator in case they are using IE
		// Creates canvas to draw the code on
		var canvas = document.createElement('canvas');
		canvas.id = "qrcode";

		// Sets the HTML5 canvas as a 2D drawing
		var context = canvas.getContext('2d');

		try
		{
			// Creates new code based on QR framework distributed by MIT
			var qr = new QRCode(typeNumber, correctionlevel);

			// Adds URL/text to QR info
			qr.addData(text);

			// Generates QR
			qr.make();
		}
		catch(err) {
			var errorP = document.createElement("p");
			var errormsg = document.createTextNode("QRFAIL: " + err);
			errorP.appendChild(errormsg);
			return errorChild;
		}

		// Dimension of QR
		var qrsize = qr.getModuleCount();
		// Cell size of each matrix cell in the QR code drawing 
		var cellsize = 10;
		// Padding around QR code
		var padding = 10;
		// Color values for the QR code
		// Dark color is passed in
		var colorlight = "rgb(255,255,255)";	// White, could be transparent later

		// Sets width and height of canvas based on number of QR matrix
		// 	cells and the set width of cell size
		canvas.setAttribute('width',qrsize*cellsize + padding);
		canvas.setAttribute('height',qrsize*cellsize + padding);
		var paddingShift = padding/2;

		// Puts canvas in the given div
		e.appendChild(canvas);

// I HATE GITHUB


		if (canvas.getContext){
			for (var r = 0; r < qrsize; r++) {
				for (var c = 0; c < qrsize; c++) {
					// Fill color for the dark portions of the QR code
					if (qr.isDark(r, c)) {
						context.fillStyle = colordark;  
					}
					// Fill color for the light portions of the QR code
					else {
						context.fillStyle = colorlight;
					}

					if (style == "standard")
					{
						// Fills in square cells
						context.fillRect((c*cellsize) + paddingShift,(r*cellsize) + paddingShift,cellsize,cellsize);  
					}
					else if (style == "circle")
					{
						//Fills in circular cells
						var radius = cellsize/2;
						context.beginPath();
				      	context.arc((c*cellsize) + paddingShift, (r*cellsize) + paddingShift, radius, 0, 2 * Math.PI, false);
					    context.fill();
					}
					else
					{
						// Fills in rounded square cells
					    // 	Uses cellsize - 1 for width/height for stylistic purposes
					    var radius = 2;
					    roundedRect(context, (c*cellsize) + paddingShift, (r*cellsize) + paddingShift, cellsize - 1, cellsize - 1, radius);

					}

					

				    

				}	
			}

		}
	}
}

// Draws a filled rounded rectangle (not included in HTML5)
// INPUTS:
//	x: top left x-coordinate
//	y: top left y-coordinate
//	width: width of rectangle
// 	height: height of rectangle
// 	radius: corner radius
function roundedRect(context, x, y, width, height, radius)
{
	context.beginPath();
	context.moveTo(x + radius, y);
 	context.lineTo(x + width - radius, y);
  	context.quadraticCurveTo(x + width, y, x + width, y + radius);
  	context.lineTo(x + width, y + height - radius);
  	context.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
  	context.lineTo(x + radius, y + height);
  	context.quadraticCurveTo(x, y + height, x, y + height - radius);
  	context.lineTo(x, y + radius);
  	context.quadraticCurveTo(x, y, x + radius, y);
  	context.closePath();
  	context.fill();
}
