<script>
// npm install chart.js@2;npm run dev
import { Doughnut } from "vue-chartjs";
export default {
  extends: Doughnut,
  data() {
    return {
      datas: {
        labels: ["A", "B"],
        datasets: [
          {
            data: [20, 60],
            backgroundColor: ["#ff525290", "#2196f390"],
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
    this.renderChart(this.datas, this.options);
  },
};
</script>