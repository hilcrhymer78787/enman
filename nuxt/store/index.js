export const state = () => ({
    loginInfo: {},
    users: [],
    todayTasks: [],
    works:{
        daily:[],
        monthly:[],
    },
})

export const mutations = {
    setLoginInfo(state, loginInfo) {
        state.loginInfo = loginInfo
    },
    setTodayTasks(state, todayTasks) {
        todayTasks.forEach((task) => {
            let minute = task.works.reduce(function (sum, work) {
                return sum + work.work_minute;
            }, 0);
            task.minute = minute
        });
        state.todayTasks = todayTasks
    },
    setWorks(state, works) {
        state.works = works
    },
}

export const actions = {
    async setLoginInfo({ commit }, form) {
        const email = form.email
        const password = form.password
        await this.$axios.get(`/api/user/read?email=${email}&password=${password}`)
            .then((res) => {
                commit('setLoginInfo', res.data)
                this.$cookies.set("token", res.data.token, {
                    maxAge: 60 * 60 * 24 * 30,
                });
            })
    },
    async setLoginInfoByToken({ commit, dispatch }) {
        const token = this.$cookies.get("token")
        if (!token) {
            return
        }
        await this.$axios.get(`/api/user/read?token=${token}`)
            .then((res) => {
                commit('setLoginInfo', res.data)
                dispatch('setTodayTasks')
            })
            .catch(() => {
                alert('通信エラーのためログイン画面に戻ります')
                this.$cookies.remove("token");
                commit("setLoginInfo", {});
                $nuxt.$router.push("/login");
            })
    },
    setTodayTasks({ state, commit }) {
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth() + 1;
        const day = today.getDate();
        this.$axios
            .get(
                `/api/task/read?year=${year}&month=${month}&day=${day}&token=${state.loginInfo.token}`
            )
            .then((res) => {
                commit('setTodayTasks', res.data)
            })
            .catch((err) => {
                alert("通信に失敗しました");
            })
    },
}