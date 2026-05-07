<?php

namespace App\Providers;

use App\Events\Auth\UsuarioRegistrado;
use App\Listeners\Auth\EnviarEmailVerificacionListener;
use App\Repositories\Contracts\UsuarioRepositoryInterface;
use App\Repositories\UsuarioRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\EntidadRepositoryInterface;
use App\Repositories\EntidadRepository;
use App\Repositories\Contracts\SitioTuristicoRepositoryInterface;
use App\Repositories\SitioTuristicoRepository;
use App\Repositories\Contracts\EventoRepositoryInterface;
use App\Repositories\EventoRepository;
use App\Repositories\Contracts\ResenaRepositoryInterface;
use App\Repositories\ResenaRepository;



// 🔹 Service Provider principal de la aplicación
class AppServiceProvider extends ServiceProvider
{
    /**
     * 🔹 Registro de servicios en el contenedor de Laravel
     */
    public function register(): void
    {
        // ====================================================
        // 🔹 BINDINGS DE REPOSITORIOS
        // ====================================================

        // 🔹 Cuando se solicite UsuarioRepositoryInterface,
        // Laravel inyectará UsuarioRepository automáticamente
        $this->app->bind(
            UsuarioRepositoryInterface::class,
            UsuarioRepository::class
        );
        $this->app->bind(EntidadRepositoryInterface::class, EntidadRepository::class);
        $this->app->bind(SitioTuristicoRepositoryInterface::class, SitioTuristicoRepository::class);
        $this->app->bind(EventoRepositoryInterface::class, EventoRepository::class);
        $this->app->bind(ResenaRepositoryInterface::class, ResenaRepository::class);
    }

    /**
     * 🔹 Método boot (se ejecuta después de register)
     */
    public function boot(): void
    {
        // 🔹 Aquí puedes ejecutar lógica al iniciar la app
        Event::listen(UsuarioRegistrado::class, EnviarEmailVerificacionListener::class);
    }
}
