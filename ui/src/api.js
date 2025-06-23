const API_URL = import.meta.env.VITE_API_URL || "http://localhost:8080/api";

export async function login(email, password) {
    const res = await fetch(`${API_URL}/login`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
    });
    if (!res.ok) throw new Error("Login failed");
    return res.json();
}

export async function getUser(token) {
    const res = await fetch(`${API_URL}/user`, {
        headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error("Unauthorized");
    return res.json();
}

export async function getIncomes(token) {
    const res = await fetch(`${API_URL}/income`, {
        headers: { Authorization: `Bearer ${token}` },
    });
    if (!res.ok) throw new Error("Unauthorized");
    return res.json();
}

export async function addIncome(token, data) {
    const res = await fetch(`${API_URL}/income`, {
        method: "POST",
        headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    });
    if (!res.ok) throw new Error("Failed to add income");
    return res.json();
}