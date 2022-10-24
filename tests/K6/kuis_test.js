
// create k6 load test with scenario

//user login,
// go to user list page and
//logout

import http from "k6/http";
import { group, check } from "k6";

export const options = {
    stages: [
        { duration: "20s", target: 10 }, // simulate ramp-up of traffic from 1 to 10 users over 20 seconds.
    ],
};

const device_name = "maulana";

export default function () {
    group("login page", function () {
        const res = http.get(`http://127.0.0.1:8070/`);
        check(res, { "is status 200": (r) => r.status === 200 });
        http.get("http://127.0.0.1:8070/");
    });

    group("list page", function () {
        let loginPage = http.get("http://127.0.0.1:8070/login");
        loginPage.submitForm({
            formSelector: "form",
            fields: { email: "user@gmail.com", password: "password" },
        });
        const res = http.get(`http://127.0.0.1:8070/dashboard`);
        check(res, { "is status 200 dashboard": (r) => r.status === 200 });
        const res1 = http.get(`http://127.0.0.1:8070/user-management/user`);
        check(res1, { "is status 200 list": (r) => r.status === 200 });
    });
    group("logout page", function () {
        let loginPage = http.get("http://127.0.0.1:8070/login");
        loginPage.submitForm({
            formSelector: "form",
            fields: { email: "user@gmail.com", password: "password" },
        });
        http.get(`http://127.0.0.1:8070/dashboard`);
        http.get(`http://127.0.0.1:8070/user-management/user`);

        const res1 = http.post(`http://127.0.0.1:8070/logout`);
        check(res1, { "is status logout 200": (r) => r.status === 200 });
    });
}

// 200 OK
// The request succeeded. The result meaning of "success" depends on the HTTP method:

// GET: The resource has been fetched and transmitted in the message body.
// HEAD: The representation headers are included in the response without any message body.
// PUT or POST: The resource describing the result of the action is transmitted in the message body.
// TRACE: The message body contains the request message as received by the server.
