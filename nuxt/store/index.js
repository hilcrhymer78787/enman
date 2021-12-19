import moment from 'moment'

export const state = () => ({
    loginInfo: null,
    todayTasks: [],
    focusTasks: [],
    calendars: [],
    calendarWorks: {
        minute: 0,
        users: [],
        tasks: [],
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
    setFocusTasks(state, focusTasks) {
        focusTasks.forEach((task) => {
            let minute = task.works.reduce(function (sum, work) {
                return sum + work.work_minute;
            }, 0);
            task.minute = minute
        });
        state.focusTasks = focusTasks
    },
    setCalendars(state, calendars) {
        state.calendars = calendars
    },
    setCalendarWorks(state, calendarWorks) {
        state.calendarWorks = calendarWorks
    },
}

export const actions = {
    setTokenRedirect({ }, token) {
        this.$cookies.set("token", token, {
            maxAge: 60 * 60 * 24 * 30,
        });
        $nuxt.$router.push("/");
    },
    setLoginInfoByToken({ commit, dispatch }) {
        this.$axios.get(`/api/user/bearer_authentication`)
            .then((res) => {
                // トークンが有効
                if (this.$cookies.get("token")) {
                    if (($nuxt.$route.name == 'login' || $nuxt.$route.name == 'login-newUser')) {
                        $nuxt.$router.push("/");
                    }
                    commit('setLoginInfo', res.data)
                }
            })
            .catch(() => {
                dispatch('logout')
            })
    },
    logout({ commit }) {
        this.$cookies.remove("token");
        if (!($nuxt.$route.name == 'login' || $nuxt.$route.name == 'login-newUser')) {
            $nuxt.$router.push("/login");
        }
        commit('setLoginInfo', false)
    },
    async setTodayTasks({ commit }) {
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth() + 1;
        const day = today.getDate();
        await this.$axios
            .get(
                `/api/task/read?year=${year}&month=${month}&day=${day}`
            )
            .then((res) => {
                commit('setTodayTasks', res.data)
            })
    },
    async setFocusTasks({ commit }) {
        const year = $nuxt.$route.query.year
        const month = $nuxt.$route.query.month
        const day = $nuxt.$route.query.day
        await this.$axios
            .get(
                `/api/task/read?year=${year}&month=${month}&day=${day}`
            )
            .then((res) => {
                commit('setFocusTasks', res.data)
            })
    },
    async setCalendars({ commit }) {
        const year = $nuxt.$route.query.year
        const month = $nuxt.$route.query.month
        let dayCount = new Date(year, month, 0).getDate();
        let dummyCalendar = []
        for(let day = 1; day <= dayCount; day++){
            let date = moment(`${year}-${month}-${day}`).format("YYYY-MM-DD")
            dummyCalendar.push({date:date})
        }
        commit("setCalendars", dummyCalendar);
        await this.$axios
            .get(`/api/work/read/calendar?year=${year}&month=${month}`)
            .then((res) => {
                if(month == $nuxt.$route.query.month){
                    commit("setCalendars", res.data.calendars);
                }
            });
    },
    async setCalendarWorks({ commit }) {
        const year = $nuxt.$route.query.year
        const month = $nuxt.$route.query.month
        const param = {
            start_date: moment(`${year}-${month}`).startOf('month').format("YYYY-MM-DD"),
            last_date: moment(`${year}-${month}`).endOf('month').format("YYYY-MM-DD"),
        }
        await this.$axios
            .post(`/api/work/read/analytics`, param)
            .then((res) => {
                commit("setCalendarWorks", res.data);
            });
    },
}