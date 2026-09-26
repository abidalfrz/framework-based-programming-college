namespace CalculatorApp
{
    partial class Form1
    {
        private System.ComponentModel.IContainer components = null;

        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        private void InitializeComponent()
        {
            this.txtDisplay = new System.Windows.Forms.TextBox();
            this.lblTitle = new System.Windows.Forms.Label();
            this.btn7 = new System.Windows.Forms.Button();
            this.btn8 = new System.Windows.Forms.Button();
            this.btn9 = new System.Windows.Forms.Button();
            this.btnDivide = new System.Windows.Forms.Button();
            this.btn4 = new System.Windows.Forms.Button();
            this.btn5 = new System.Windows.Forms.Button();
            this.btn6 = new System.Windows.Forms.Button();
            this.btnMultiply = new System.Windows.Forms.Button();
            this.btn1 = new System.Windows.Forms.Button();
            this.btn2 = new System.Windows.Forms.Button();
            this.btn3 = new System.Windows.Forms.Button();
            this.btnMinus = new System.Windows.Forms.Button();
            this.btn0 = new System.Windows.Forms.Button();
            this.btnDecimal = new System.Windows.Forms.Button();
            this.btnClear = new System.Windows.Forms.Button();
            this.btnPlus = new System.Windows.Forms.Button();
            this.btnEquals = new System.Windows.Forms.Button();
            this.SuspendLayout();
            
            // txtDisplay
            this.txtDisplay.Font = new System.Drawing.Font("Segoe UI", 18F, System.Drawing.FontStyle.Bold);
            this.txtDisplay.Location = new System.Drawing.Point(20, 60);
            this.txtDisplay.Name = "txtDisplay";
            this.txtDisplay.ReadOnly = true;
            this.txtDisplay.Size = new System.Drawing.Size(260, 39);
            this.txtDisplay.TabIndex = 0;
            this.txtDisplay.Text = "0";
            this.txtDisplay.TextAlign = System.Windows.Forms.HorizontalAlignment.Right;
            
            // lblTitle
            this.lblTitle.AutoSize = true;
            this.lblTitle.Font = new System.Drawing.Font("Segoe UI", 16F, System.Drawing.FontStyle.Bold);
            this.lblTitle.Location = new System.Drawing.Point(20, 15);
            this.lblTitle.Name = "lblTitle";
            this.lblTitle.Size = new System.Drawing.Size(118, 30);
            this.lblTitle.TabIndex = 1;
            this.lblTitle.Text = "Calculator";
            
            // btn7
            this.btn7.Location = new System.Drawing.Point(20, 110);
            this.btn7.Name = "btn7";
            this.btn7.Size = new System.Drawing.Size(60, 50);
            this.btn7.TabIndex = 2;
            this.btn7.Text = "7";
            this.btn7.UseVisualStyleBackColor = true;
            this.btn7.Click += new System.EventHandler(this.NumberButton_Click);
            
            // btn8
            this.btn8.Location = new System.Drawing.Point(86, 110);
            this.btn8.Name = "btn8";
            this.btn8.Size = new System.Drawing.Size(60, 50);
            this.btn8.TabIndex = 3;
            this.btn8.Text = "8";
            this.btn8.UseVisualStyleBackColor = true;
            this.btn8.Click += new System.EventHandler(this.NumberButton_Click);
            
            // btn9
            this.btn9.Location = new System.Drawing.Point(152, 110);
            this.btn9.Name = "btn9";
            this.btn9.Size = new System.Drawing.Size(60, 50);
            this.btn9.TabIndex = 4;
            this.btn9.Text = "9";
            this.btn9.UseVisualStyleBackColor = true;
            this.btn9.Click += new System.EventHandler(this.NumberButton_Click);
            
            // btnDivide
            this.btnDivide.Location = new System.Drawing.Point(218, 110);
            this.btnDivide.Name = "btnDivide";
            this.btnDivide.Size = new System.Drawing.Size(60, 50);
            this.btnDivide.TabIndex = 5;
            this.btnDivide.Text = "÷";
            this.btnDivide.UseVisualStyleBackColor = true;
            this.btnDivide.Click += new System.EventHandler(this.OperatorButton_Click);
            this.btnDivide.BackColor = System.Drawing.Color.FromArgb(255, 159, 10);
            this.btnDivide.ForeColor = System.Drawing.Color.White;
            this.btnDivide.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.btnDivide.FlatAppearance.BorderSize = 0;
            
            // btn4
            this.btn4.Location = new System.Drawing.Point(20, 166);
            this.btn4.Name = "btn4";
            this.btn4.Size = new System.Drawing.Size(60, 50);
            this.btn4.TabIndex = 6;
            this.btn4.Text = "4";
            this.btn4.UseVisualStyleBackColor = true;
            this.btn4.Click += new System.EventHandler(this.NumberButton_Click);
            
            // btn5
            this.btn5.Location = new System.Drawing.Point(86, 166);
            this.btn5.Name = "btn5";
            this.btn5.Size = new System.Drawing.Size(60, 50);
            this.btn5.TabIndex = 7;
            this.btn5.Text = "5";
            this.btn5.UseVisualStyleBackColor = true;
            this.btn5.Click += new System.EventHandler(this.NumberButton_Click);
            
            // btn6
            this.btn6.Location = new System.Drawing.Point(152, 166);
            this.btn6.Name = "btn6";
            this.btn6.Size = new System.Drawing.Size(60, 50);
            this.btn6.TabIndex = 8;
            this.btn6.Text = "6";
            this.btn6.UseVisualStyleBackColor = true;
            this.btn6.Click += new System.EventHandler(this.NumberButton_Click);
            
            // btnMultiply
            this.btnMultiply.Location = new System.Drawing.Point(218, 166);
            this.btnMultiply.Name = "btnMultiply";
            this.btnMultiply.Size = new System.Drawing.Size(60, 50);
            this.btnMultiply.TabIndex = 9;
            this.btnMultiply.Text = "x";
            this.btnMultiply.UseVisualStyleBackColor = true;
            this.btnMultiply.Click += new System.EventHandler(this.OperatorButton_Click);
            this.btnMultiply.BackColor = System.Drawing.Color.FromArgb(255, 159, 10);
            this.btnMultiply.ForeColor = System.Drawing.Color.White;
            this.btnMultiply.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.btnMultiply.FlatAppearance.BorderSize = 0;
            
            // btn1
            this.btn1.Location = new System.Drawing.Point(20, 222);
            this.btn1.Name = "btn1";
            this.btn1.Size = new System.Drawing.Size(60, 50);
            this.btn1.TabIndex = 10;
            this.btn1.Text = "1";
            this.btn1.UseVisualStyleBackColor = true;
            this.btn1.Click += new System.EventHandler(this.NumberButton_Click);
            
            // btn2
            this.btn2.Location = new System.Drawing.Point(86, 222);
            this.btn2.Name = "btn2";
            this.btn2.Size = new System.Drawing.Size(60, 50);
            this.btn2.TabIndex = 11;
            this.btn2.Text = "2";
            this.btn2.UseVisualStyleBackColor = true;
            this.btn2.Click += new System.EventHandler(this.NumberButton_Click);
            
            // btn3
            this.btn3.Location = new System.Drawing.Point(152, 222);
            this.btn3.Name = "btn3";
            this.btn3.Size = new System.Drawing.Size(60, 50);
            this.btn3.TabIndex = 12;
            this.btn3.Text = "3";
            this.btn3.UseVisualStyleBackColor = true;
            this.btn3.Click += new System.EventHandler(this.NumberButton_Click);
            
            // btnMinus
            this.btnMinus.Location = new System.Drawing.Point(218, 222);
            this.btnMinus.Name = "btnMinus";
            this.btnMinus.Size = new System.Drawing.Size(60, 50);
            this.btnMinus.TabIndex = 13;
            this.btnMinus.Text = "-";
            this.btnMinus.UseVisualStyleBackColor = true;
            this.btnMinus.Click += new System.EventHandler(this.OperatorButton_Click);
            this.btnMinus.BackColor = System.Drawing.Color.FromArgb(255, 159, 10);
            this.btnMinus.ForeColor = System.Drawing.Color.White;
            this.btnMinus.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.btnMinus.FlatAppearance.BorderSize = 0;
            
            // btn0
            this.btn0.Location = new System.Drawing.Point(20, 278);
            this.btn0.Name = "btn0";
            this.btn0.Size = new System.Drawing.Size(60, 50);
            this.btn0.TabIndex = 14;
            this.btn0.Text = "0";
            this.btn0.UseVisualStyleBackColor = true;
            this.btn0.Click += new System.EventHandler(this.NumberButton_Click);
            
            // btnDecimal
            this.btnDecimal.Location = new System.Drawing.Point(86, 278);
            this.btnDecimal.Name = "btnDecimal";
            this.btnDecimal.Size = new System.Drawing.Size(60, 50);
            this.btnDecimal.TabIndex = 15;
            this.btnDecimal.Text = ".";
            this.btnDecimal.UseVisualStyleBackColor = true;
            this.btnDecimal.Click += new System.EventHandler(this.btnDecimal_Click);
            
            // btnClear
            this.btnClear.Location = new System.Drawing.Point(152, 278);
            this.btnClear.Name = "btnClear";
            this.btnClear.Size = new System.Drawing.Size(60, 50);
            this.btnClear.TabIndex = 16;
            this.btnClear.Text = "C";
            this.btnClear.UseVisualStyleBackColor = true;
            this.btnClear.Click += new System.EventHandler(this.btnClear_Click);
            this.btnClear.BackColor = System.Drawing.Color.FromArgb(235, 87, 87);
            this.btnClear.ForeColor = System.Drawing.Color.White;
            this.btnClear.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.btnClear.FlatAppearance.BorderSize = 0;
            
            // btnPlus
            this.btnPlus.Location = new System.Drawing.Point(218, 278);
            this.btnPlus.Name = "btnPlus";
            this.btnPlus.Size = new System.Drawing.Size(60, 50);
            this.btnPlus.TabIndex = 17;
            this.btnPlus.Text = "+";
            this.btnPlus.UseVisualStyleBackColor = true;
            this.btnPlus.Click += new System.EventHandler(this.OperatorButton_Click);
            this.btnPlus.BackColor = System.Drawing.Color.FromArgb(255, 159, 10);
            this.btnPlus.ForeColor = System.Drawing.Color.White;
            this.btnPlus.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.btnPlus.FlatAppearance.BorderSize = 0;
            
            // btnEquals
            this.btnEquals.Location = new System.Drawing.Point(20, 334);
            this.btnEquals.Name = "btnEquals";
            this.btnEquals.Size = new System.Drawing.Size(258, 50);
            this.btnEquals.TabIndex = 18;
            this.btnEquals.Text = "=";
            this.btnEquals.UseVisualStyleBackColor = true;
            this.btnEquals.Click += new System.EventHandler(this.btnEquals_Click);
            this.btnEquals.BackColor = System.Drawing.Color.FromArgb(40, 167, 69);
            this.btnEquals.ForeColor = System.Drawing.Color.White;
            this.btnEquals.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.btnEquals.FlatAppearance.BorderSize = 0;
            
            // Form1
            this.ClientSize = new System.Drawing.Size(298, 404);
            this.Controls.Add(this.btnEquals);
            this.Controls.Add(this.btnPlus);
            this.Controls.Add(this.btnClear);
            this.Controls.Add(this.btnDecimal);
            this.Controls.Add(this.btn0);
            this.Controls.Add(this.btnMinus);
            this.Controls.Add(this.btn3);
            this.Controls.Add(this.btn2);
            this.Controls.Add(this.btn1);
            this.Controls.Add(this.btnMultiply);
            this.Controls.Add(this.btn6);
            this.Controls.Add(this.btn5);
            this.Controls.Add(this.btn4);
            this.Controls.Add(this.btnDivide);
            this.Controls.Add(this.btn9);
            this.Controls.Add(this.btn8);
            this.Controls.Add(this.btn7);
            this.Controls.Add(this.lblTitle);
            this.Controls.Add(this.txtDisplay);
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle;
            this.MaximizeBox = false;
            this.Name = "Form1";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
            this.Text = "Calculator";
            this.ResumeLayout(false);
            this.PerformLayout();

            // Keyboard event handling
            this.KeyPreview = true;
            this.KeyDown += new System.Windows.Forms.KeyEventHandler(this.Form1_KeyDown);
        }

        #endregion

        private System.Windows.Forms.TextBox txtDisplay;
        private System.Windows.Forms.Label lblTitle;
        private System.Windows.Forms.Button btn7;
        private System.Windows.Forms.Button btn8;
        private System.Windows.Forms.Button btn9;
        private System.Windows.Forms.Button btnDivide;
        private System.Windows.Forms.Button btn4;
        private System.Windows.Forms.Button btn5;
        private System.Windows.Forms.Button btn6;
        private System.Windows.Forms.Button btnMultiply;
        private System.Windows.Forms.Button btn1;
        private System.Windows.Forms.Button btn2;
        private System.Windows.Forms.Button btn3;
        private System.Windows.Forms.Button btnMinus;
        private System.Windows.Forms.Button btn0;
        private System.Windows.Forms.Button btnDecimal;
        private System.Windows.Forms.Button btnClear;
        private System.Windows.Forms.Button btnPlus;
        private System.Windows.Forms.Button btnEquals;
    }
}