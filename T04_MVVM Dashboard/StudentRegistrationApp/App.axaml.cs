using Avalonia;
using Avalonia.Controls.ApplicationLifetimes;
using Avalonia.Markup.Xaml;

using StudentRegistrationApp.Repositories;
using StudentRegistrationApp.ViewModels;
using StudentRegistrationApp.Views;

namespace StudentRegistrationApp
{
    public partial class App : Application
    {
        public override void Initialize(){
            AvaloniaXamlLoader.Load(this);
        }

        public override void OnFrameworkInitializationCompleted(){
            if (
                ApplicationLifetime
                is IClassicDesktopStyleApplicationLifetime desktop
            ){
                IMahasiswaRepository repository =
                    new MahasiswaRepository();

                MainWindowViewModel viewModel =
                    new MainWindowViewModel(
                        repository
                    );

                desktop.MainWindow =
                    new MainWindow{
                        DataContext = viewModel
                    };
            }

            base.OnFrameworkInitializationCompleted();
        }
    }
}