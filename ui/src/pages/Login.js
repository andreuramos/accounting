import React, { useState } from "react";

export default function Login({ onLogin }) {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [error, setError] = useState("");

    const handleSubmit = async (e) => {
        e.preventDefault();
        // Call your login handler here
        if (!email || !password) {
            setError("Email and password are required.");
            return;
        }
        setError("");
        onLogin?.(email, password);
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-700">
            <div className="bg-white rounded-xl shadow-2xl p-8 w-full max-w-md">
                <h1 className="text-3xl font-extrabold text-indigo-700 mb-6 text-center tracking-tight">
                    Welcome Back!
                </h1>
                <form className="space-y-6" onSubmit={handleSubmit}>
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input
                            type="email"
                            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none transition"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            placeholder="you@example.com"
                            autoFocus
                            required
                        />
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">
                            Password
                        </label>
                        <input
                            type="password"
                            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none transition"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            placeholder="********"
                            required
                        />
                    </div>
                    {error && (
                        <div className="text-red-600 text-sm font-semibold text-center">
                            {error}
                        </div>
                    )}
                    <button
                        type="submit"
                        className="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition transform hover:-translate-y-1 hover:shadow-lg"
                    >
                        Login
                    </button>
                </form>
                <div className="mt-6 text-center text-gray-500 text-sm">
                    Don't have an account?{" "}
                    <a
                        href="/register"
                        className="text-indigo-600 hover:underline font-medium"
                    >
                        Sign up
                    </a>
                </div>
            </div>
        </div>
    );
}