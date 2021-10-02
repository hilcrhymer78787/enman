<script>
// npm install chart.js@2;npm run dev
import { Doughnut } from "vue-chartjs";
export default {
  extends: Doughnut,
  props: ["workUsers"],
  data() {
    return {
      datas: {
        labels: ["A", "B", "C"],
        datasets: [
          {
            data: [],
            backgroundColor: ["#2196f390","#ff525290"],
          },
        ],
      },
      options: {
        responsive: true,
        legend: {
          display: false,
        },
      },
    };
  },
  mounted() {
    this.workUsers.forEach(user => {
        this.datas.datasets[0].data.push(user.minute)
        this.datas.labels.push(user.name)
    });
    this.renderChart(this.datas, this.options);
  },
};
</script>