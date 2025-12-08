import { Link } from '@inertiajs/inertia-react';
import { useState } from 'react';
import ApplicationLogo from '@/Components/ApplicationLogo';

export default function Navbar({ auth, scroll }) {
    const [mobileOpen, setMobileOpen] = useState(false);
console.log(auth);
    return (
        <nav className={scroll ? "bg-white shadow-md fixed inset-x-0 z-20" : "navi-bar fixed inset-x-0 z-20"}>
            <div className="max-w-screen-xl mx-auto px-3">
                <div className="flex justify-between items-center py-2">
                    {/* Logo */}
                    <div className="flex items-center">
                        <Link href={route("homepage")}>
                            <ApplicationLogo />
                        </Link>
                        <span className="uppercase font-semibold ml-2 text-2xl text-neutral-800">Domani lavoro</span>
                    </div>

                    {/* Desktop Menu */}
                    <div className="hidden md:flex items-center space-x-4">
                        {!auth?.user && (
                            <>
                                <Link href={route("login")} className="text-blue-500 hover:underline">Login</Link>
                                <Link href={route("register")} className="text-blue-500 hover:underline">Registrati</Link>
                            </>
                        )}
                        {auth?.user && (
                            <>
                                <span className="text-gray-700">Ciao, {auth.user.name}</span>
                                <Link href={route("dashboard")} className="text-blue-500 hover:underline">Dashboard</Link>
                                <Link href={route("logout")} method="post" className="text-red-500 hover:underline">Logout</Link>
                            </>
                        )}
                    </div>

                    {/* Hamburger Mobile */}
                    <button
                        className="md:hidden text-gray-700"
                        onClick={() => setMobileOpen(!mobileOpen)}
                    >
                        ☰
                    </button>
                </div>

                {/* Mobile Menu */}
                {mobileOpen && (
                    <div className="md:hidden flex flex-col space-y-2 py-2">
                        {!auth?.user && (
                            <>
                                <Link href={route("login")} className="text-blue-500 hover:underline">Login</Link>
                                <Link href={route("register")} className="text-blue-500 hover:underline">Registrati</Link>
                            </>
                        )}
                        {auth?.user && (
                            <>
                                <span className="text-gray-700">Ciao, {auth.user.name}</span>
                                <Link href={route("dashboard")} className="text-blue-500 hover:underline">Dashboard</Link>
                                <Link href={route("logout")} method="post" className="text-red-500 hover:underline">Logout</Link>
                            </>
                        )}
                    </div>
                )}
            </div>
        </nav>
    );
}
