/*
import React from "react";
import Navbar from "@/Components/Navbar";
import { useScroll } from "@/Hooks/useScroll";

export default function Front({ children }) {
    const scroll = useScroll();

    return (
        <div className="flex flex-col min-h-screen bg-zinc-50">
            <Navbar scroll={scroll} />

            <div className="main-content flex-grow min-h-full pb-10">{children}</div>
        </div>
    );
}
*/
import React from "react";
import Navbar from "@/Components/Navbar";
import { useScroll } from "@/Hooks/useScroll";
import { usePage } from "@inertiajs/inertia-react";

export default function Front({ children }) {
    const scroll = useScroll();
    const { auth, flash } = usePage().props; // prende i flash messages da Inertia

    return (
        <div className="flex flex-col min-h-screen bg-zinc-50">
            <Navbar scroll={scroll} auth={auth} />

            {/* Mostra messaggi flash */}
            <div className="max-w-7xl mx-auto mt-4 px-3">
                {flash.error && (
                    <div className="fixed top-16 left-1/2 transform -translate-x-1/2 bg-red-500 text-white p-3 rounded z-50 shadow-lg">
                        {flash.error}
                    </div>
                )}
                {flash.success && (
                    <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {flash.success}
                    </div>
                )}
            </div>

            <div className="main-content flex-grow min-h-full pb-10">{children}</div>
        </div>
    );
}
