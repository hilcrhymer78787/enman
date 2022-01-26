import moment from 'moment'
import axios from 'axios'
const CancelToken = axios.CancelToken;
let setCalendarsCancel = null;
let setCalendarWorksCancel = null;
let setLoginInfoByTokenCancel = null;

export const state = () => ({
    loginInfo: null,
    todayTasks: [],
    focusTasks: [],
    calendars: [],
    calendarWorks: null,
})

export const mutations = {
    setLoginInfo(state, loginInfo) {
        state.loginInfo = loginInfo
    },
    setTodayTasks(state, todayTasks) {
        state.todayTasks = todayTasks
    },
    setFocusTasks(state, focusTasks) {
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
        if (setLoginInfoByTokenCancel) {
            setLoginInfoByTokenCancel()
        }
        this.$axios.get(`/api/user/bearer_authentication`, {
            cancelToken: new CancelToken(c => {
                setLoginInfoByTokenCancel = c
            }),
        })
            .then((res) => {
                // トークンが有効
                if (this.$cookies.get("token")) {
                    if (($nuxt.$route.name == 'login' || $nuxt.$route.name == 'login-newUser')) {
                        $nuxt.$router.push("/");
                    }
                    commit('setLoginInfo', res.data)
                }
            })
            .catch((err) => {
                if (err.response) {
                    dispatch('logout')
                }
                if (err.response && err.response.status == 429) {
                    alert('一定時間にアクセスが集中したため、しばらくアクセスできません')
                }
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
        if (setCalendarsCancel) {
            setCalendarsCancel()
        }
        await this.$axios
            .get(`/api/work/read/calendar?year=${year}&month=${month}`, {
                cancelToken: new CancelToken(c => {
                    setCalendarsCancel = c
                }),
            })
            .then((res) => {
                commit("setCalendars", res.data.calendars);
            });
    },
    async setCalendarWorks({ commit }) {
        commit("setCalendarWorks", null);
        const year = $nuxt.$route.query.year
        const month = $nuxt.$route.query.month
        const param = {
            start_date: moment(`${year}/${month}/1`).startOf('month').format("YYYY-MM-DD"),
            last_date: moment(`${year}/${month}/1`).endOf('month').format("YYYY-MM-DD"),
        }
        if (setCalendarWorksCancel) {
            setCalendarWorksCancel()
        }
        await this.$axios
            .post(`/api/work/read/analytics`, param, {
                cancelToken: new CancelToken(c => {
                    setCalendarWorksCancel = c
                }),
            })
            .then((res) => {
                commit("setCalendarWorks", res.data);
            });
    },
}