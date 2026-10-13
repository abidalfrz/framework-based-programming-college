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
                IMahasiswaRepository mahasiswaRepository =
                    new MahasiswaRepository();

                IProgramRepository programRepository =
                    new ProgramRepository();

                MainWindowViewModel viewModel =
                    new MainWindowViewModel(
                        mahasiswaRepository,
                        programRepository
                    );

                desktop.MainWindow = new MainWindow{
                    DataContext = viewModel
                };
            }

            base.OnFrameworkInitializationCompleted();
        }
    }
}