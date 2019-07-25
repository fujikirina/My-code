using System;
using System.Windows.Forms;
using System.Xml.Linq;
using System.Xml;
using System.Drawing;
using System.ComponentModel;

namespace todoList
{
    public partial class ToDoList : Form
    {
        public ToDoList()
        {
            InitializeComponent();
        }

        private void GetData()
        {
            dataGridView1.Rows.Clear();

            //xmlファイルの読み込み
            string dir = AppDomain.CurrentDomain.BaseDirectory.TrimEnd('\\');
            string updir = dir.Substring(0, dir.LastIndexOf(@"\bin") + 1);
            XDocument xml = XDocument.Load(updir + "data.xml");

            //データの取得
            XElement table = xml.Element("data");
            var rows = table.Elements("line");

            int i = 0;

            //今日の日付
            DateTime now = DateTime.Now;
            string day = now.ToShortDateString();
            displayButton.Visible = true;
            hiddunButton.Visible = false;

            foreach (XElement row in rows)
            {
                XElement submit = row.Element("submit");
                var num1 = Boolean.Parse(submit.Value);
                XElement date = row.Element("date");
                XElement todo = row.Element("todo");

                //チェックボックスに入力があったら表示しない
                dataGridView1.Rows.Add(num1, date.Value, todo.Value);
                if (num1)
                {
                    dataGridView1.Rows[i].Visible = false;
                }

                //日付が過ぎたものは背景をグレーにする
                if (day.CompareTo(dataGridView1.Rows[i].Cells[1].Value) == 0 || day.CompareTo(dataGridView1.Rows[i].Cells[1].Value) == 1)
                {
                    dataGridView1.Rows[i].DefaultCellStyle.BackColor = Color.Gainsboro;
                }

                i++;
            }
        }

        private void SaveData()
        {
            //データの保存
            string dir = AppDomain.CurrentDomain.BaseDirectory.TrimEnd('\\');
            string updir = dir.Substring(0, dir.LastIndexOf(@"\bin") + 1);
            XmlDocument xmlDocument = new XmlDocument();
            XmlElement xmlData = xmlDocument.CreateElement("data");
            xmlDocument.AppendChild(xmlData);
            for (int n = 0; n < dataGridView1.Rows.Count; n++)
            {
                XmlElement lineElem = xmlDocument.CreateElement("line");
                xmlData.AppendChild(lineElem);
                XmlElement submitElem = xmlDocument.CreateElement("submit");
                lineElem.AppendChild(submitElem);
                XmlNode submitNode = xmlDocument.CreateNode(XmlNodeType.Text, "", "");
                submitNode.Value = (dataGridView1.Rows[n].Cells[0].Value).ToString();
                submitElem.AppendChild(submitNode);
                XmlElement dateElem = xmlDocument.CreateElement("date");
                lineElem.AppendChild(dateElem);
                XmlNode dateNode = xmlDocument.CreateNode(XmlNodeType.Text, "", "");
                dateNode.Value = (dataGridView1.Rows[n].Cells[1].Value).ToString();
                dateElem.AppendChild(dateNode);
                XmlElement todoElem = xmlDocument.CreateElement("todo");
                lineElem.AppendChild(todoElem);
                XmlNode todoNode = xmlDocument.CreateNode(XmlNodeType.Text, "", "");
                todoNode.Value = (dataGridView1.Rows[n].Cells[2].Value).ToString();
                todoElem.AppendChild(todoNode);
            }
            xmlDocument.Save(updir + "data.xml");
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            //列の幅と高さの変更不可
            dataGridView1.AllowUserToResizeColumns = false;
            dataGridView1.AllowUserToResizeRows = false;
            //新しい行の追加を非表示
            dataGridView1.AllowUserToAddRows = false;
            //行ヘッダーを非表示
            dataGridView1.RowHeadersVisible = false;
            //行と列の幅を調整
            dataGridView1.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.Fill;
            dataGridView1.AutoSizeRowsMode = DataGridViewAutoSizeRowsMode.None;
            //選択は行単位
            dataGridView1.SelectionMode = DataGridViewSelectionMode.FullRowSelect;

            //列の追加
            dataGridView1.Columns.Add(new DataGridViewCheckBoxColumn());
            dataGridView1.ColumnCount = 3;
            dataGridView1.Columns[0].HeaderText = "完了";
            dataGridView1.Columns[1].HeaderText = "期限";
            dataGridView1.Columns[2].HeaderText = "やること";
            dataGridView1.Columns[0].Width = 60;
            dataGridView1.Columns[1].Width = 105;

            //コンテキストメニューの追加
            dataGridView1.ContextMenuStrip = this.contextMenuStrip1;

            GetData();

        }

        private void submitButton_Click(object sender, EventArgs e)
        {
            //テキストボックスに入力があった場合
            if (textBox1.Text.Length > 0)
            {
                string textValue = textBox1.Text;
                string dateValue = dateTimePicker1.Text;
                dataGridView1.Rows.Add("False", dateValue, textValue);
            }

            SaveData();
            GetData();

            //データを日付の昇順で並び替える
            dataGridView1.Sort(dataGridView1.Columns[1], ListSortDirection.Ascending);
        }

        private void allDeleteButton_Click(object sender, EventArgs e)
        {
            //警告してOKが押されたら表示データを全削除する
            DialogResult result = MessageBox.Show("現在表示しているリストが全部削除されます！" + Environment.NewLine + "本当に削除しますか？",
                "警告！",
                MessageBoxButtons.OKCancel,
                MessageBoxIcon.Exclamation);

            if (result == DialogResult.OK)
            {
                dataGridView1.Rows.Clear();
                SaveData();
            }
        }

            private void 削除ToolStripMenuItem_Click(object sender, EventArgs e)
        {
            // 選択されている行をすべて削除する
            foreach (DataGridViewRow row in this.dataGridView1.SelectedRows)
            {
                this.dataGridView1.Rows.Remove(row);
            }
            SaveData();
        }

        private void displayButton_Click_1(object sender, EventArgs e)
        {
            SaveData();

            //完了済リストも全て表示
            for (int i=0; i<dataGridView1.Rows.Count; i++)
            {
                dataGridView1.Rows[i].Visible = true;
            }
            hiddunButton.Visible = true;
            displayButton.Visible = false;
        }

        private void hiddunButton_Click(object sender, EventArgs e)
        {
            displayButton.Visible = true;
            hiddunButton.Visible = false;

            SaveData();
            GetData();
        }
    }
}
