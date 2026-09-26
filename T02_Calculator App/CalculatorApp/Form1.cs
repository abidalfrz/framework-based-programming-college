using System;
using System.Windows.Forms;

namespace CalculatorApp
{
    public partial class Form1 : Form
    {
        private decimal firstNumber = 0;
        private decimal secondNumber = 0;
        private decimal result = 0;
        private string operation = "";

        public Form1()
        {
            InitializeComponent();
        }

        private void NumberButton_Click(object sender, EventArgs e)
        {
            Button button = (Button)sender;

            if (txtDisplay.Text == "0"){
                txtDisplay.Text = button.Text;
            }
            else{
                txtDisplay.Text += button.Text;
            }
        }

        private void OperatorButton_Click(object sender, EventArgs e)
        {
            Button button = (Button)sender;

            firstNumber = decimal.Parse(txtDisplay.Text);
            operation = button.Text;
            txtDisplay.Clear();
        }

        private void btnEquals_Click(object sender, EventArgs e)
        {
            try
            {
                secondNumber = decimal.Parse(txtDisplay.Text);

                switch (operation)
                {
                    case "+":
                        result = firstNumber + secondNumber;
                        break;
                    case "-":
                        result = firstNumber - secondNumber;
                        break;
                    case "x":
                        result = firstNumber * secondNumber;
                        break;
                    case "÷":
                        if (secondNumber == 0)
                        {
                            throw new DivideByZeroException("Tidak dapat membagi dengan nol!");
                        }
                        result = firstNumber / secondNumber;
                        break;
                    default:
                        result = secondNumber;
                        break;
                }

                txtDisplay.Text = result.ToString();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message, "Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void btnClear_Click(object sender, EventArgs e)
        {
            firstNumber = 0;
            secondNumber = 0;
            result = 0;
            operation = "";
            txtDisplay.Text = "0";
        }

        private void btnDecimal_Click(object sender, EventArgs e)
        {
            if (!txtDisplay.Text.Contains("."))
            {
                txtDisplay.Text += ".";
            }
        }

        private void Form1_KeyDown(object sender, KeyEventArgs e)
        {
            if (e.Shift && e.KeyCode == Keys.D8) 
            {
                SimulateOperatorClick("x"); 
                e.Handled = true;
            }
            else if (e.Shift && e.KeyCode == Keys.Oemplus) 
            {
                SimulateOperatorClick("+");
                e.Handled = true;
            }
            else if (e.KeyCode == Keys.Multiply) 
            {
                SimulateOperatorClick("x");
                e.Handled = true;
            }
            else if (e.KeyCode == Keys.Add) 
            {
                SimulateOperatorClick("+");
                e.Handled = true;
            }
            else if (e.KeyCode == Keys.Subtract || e.KeyCode == Keys.OemMinus) // Pengurangan (-)
            {
                SimulateOperatorClick("-");
                e.Handled = true;
            }
            else if (e.KeyCode == Keys.Divide || e.KeyCode == Keys.OemQuestion) // Pembagian (/)
            {
                SimulateOperatorClick("÷");
                e.Handled = true;
            }
            else if ((e.KeyCode >= Keys.D0 && e.KeyCode <= Keys.D9) || 
                    (e.KeyCode >= Keys.NumPad0 && e.KeyCode <= Keys.NumPad9))
            {
                string digit = e.KeyCode.ToString().Replace("D", "").Replace("NumPad", "");
                SimulateNumberClick(digit);
                e.Handled = true;
            }
            else if (e.KeyCode == Keys.Enter || (e.KeyCode == Keys.Oemplus && !e.Shift))
            {
                btnEquals.PerformClick();
                e.Handled = true;
            }
            else if (e.KeyCode == Keys.Decimal || e.KeyCode == Keys.OemPeriod || e.KeyCode == Keys.Oemcomma)
            {
                btnDecimal.PerformClick();
                e.Handled = true;
            }
            else if (e.KeyCode == Keys.Escape)
            {
                btnClear.PerformClick();
                e.Handled = true;
            }
            else if (e.KeyCode == Keys.Back)
            {
                if (txtDisplay.Text.Length > 1)
                {
                    txtDisplay.Text = txtDisplay.Text.Substring(0, txtDisplay.Text.Length - 1);
                }
                else
                {
                    txtDisplay.Text = "0";
                }
                e.Handled = true;
            }
        }

        private void SimulateNumberClick(string number)
        {
            if (txtDisplay.Text == "0")
                txtDisplay.Text = number;
            else
                txtDisplay.Text += number;
        }

        private void SimulateOperatorClick(string op)
        {
            firstNumber = decimal.Parse(txtDisplay.Text);
            operation = op;
            txtDisplay.Clear();
        }
    }
}