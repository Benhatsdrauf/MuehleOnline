

export default async function AuthorizedRequest(url, data, method = "Post") {
    return await fetch(url, {
        method: method,
        mode: "cors",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,

        },
        body: JSON.stringify(data),
    })
        .then((response) => {
            if (response.ok) {
                return response.json();

            } else {
                return Promise.reject(response);
            }
        })

}