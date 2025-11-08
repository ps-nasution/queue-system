import React, { lazy } from "react";
import { createBrowserRouter, RouterProvider, Navigate } from "react-router-dom";

const Kiosk = lazy(() => import("./pages/Kiosk"));
const StaffConsole = lazy(() => import("./pages/StaffConsole"));
const Dashboard = lazy(() => import("./pages/Dashboard"));
const Display = lazy(() => import("./pages/Display"));

import { Outlet, Link } from "react-router-dom";
function Layout() {
    return (
        <div className="min-h-screen">
            <nav className="flex gap-4 p-4 shadow sticky top-0 bg-white">
                <Link to="/">Home</Link>
                <Link to="/staff">Staff</Link>
                <Link to="/dashboard">Dashboard</Link>
                <Link to="/display">Display</Link>
            </nav>
 
            <main>
                <React.Suspense fallback={<div className="p-6">Loading…</div>}>
                    <Outlet />
                </React.Suspense>
            </main>
        </div>
    );
}

function NotFound() {
    return (
        <section className="p-6">
            <h1 className="text-2xl font-bold">404</h1>
            <p>Halaman tidak ditemukan.</p>
            <a href="/">Kembali ke beranda</a>
        </section>
    );
}

function ErrorBoundaryPage() {
    return (
        <section className="p-6">
            <h1 className="text-2xl font-bold">Terjadi kesalahan</h1>
            <p>Silakan muat ulang halaman.</p>
        </section>
    );
}

// ----- Definisi router v7 -----
export const router = createBrowserRouter([
    {
        path: "/",
        element: <Layout />,
        errorElement: <ErrorBoundaryPage />,
        children: [
            { index: true, element: <Navigate to="/kiosk" replace /> },
            { path: "kiosk", element: <Kiosk /> },
            { path: "staff", element: <StaffConsole /> },
            { path: "dashboard", element: <Dashboard /> },
            { path: "display", element: <Display /> },
            { path: "*", element: <NotFound /> },
        ],
    },
]);


export default function AppRouter() {
    return <RouterProvider router={router} fallbackElement={<div>Loading…</div>} />;
}
